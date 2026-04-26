<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class SyncFleetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-fleet-data {--repair : Automatically fix inconsistencies found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit and repair data drift between vehicle services JSON and the service_logs table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Fleet Data Health Check...');
        $repair = $this->option('repair');
        
        $totalVehicles = Vehicle::count();
        $bar = $this->output->createProgressBar($totalVehicles);
        $bar->start();

        $stats = [
            'scanned' => 0,
            'drift_detected' => 0,
            'repaired' => 0,
            'total_cost_fixed' => 0,
        ];

        Vehicle::chunk(100, function ($vehicles) use ($bar, $repair, &$stats) {
            foreach ($vehicles as $vehicle) {
                $hasDrift = false;
                
                // 1. Audit Service Synchronization
                // We use the existing sync logic but track if it changes anything
                $oldStatus = $vehicle->status;
                $vehicle->syncServiceLogs();
                
                if ($oldStatus !== $vehicle->status) {
                    $hasDrift = true;
                }

                // 2. Audit Total Cost
                $actualTotal = $vehicle->serviceLogs()->sum('cost');
                if ((float)$vehicle->total_cost !== (float)$actualTotal) {
                    $hasDrift = true;
                    if ($repair) {
                        $vehicle->update(['total_cost' => $actualTotal]);
                        $stats['total_cost_fixed']++;
                    }
                }

                if ($hasDrift) {
                    $stats['drift_detected']++;
                    if ($repair) {
                        $stats['repaired']++;
                    }
                }

                $stats['scanned']++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Vehicles Scanned', $stats['scanned']],
                ['Inconsistencies Detected', $stats['drift_detected']],
                ['Inconsistencies Repaired', $stats['repaired']],
                ['Cost Balances Corrected', $stats['total_cost_fixed']],
            ]
        );

        if (!$repair && $stats['drift_detected'] > 0) {
            $this->warn('Drift detected! Run with --repair to fix these issues automatically.');
        } else {
            $this->info('Fleet data is in healthy synchronization.');
        }
    }
}
