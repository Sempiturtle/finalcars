<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncLoyaltyRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:sync-rewards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize loyalty rewards based on existing service types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Synchronizing loyalty rewards with service types...');

        $serviceTypes = \App\Models\ServiceType::all();
        $count = 0;

        foreach ($serviceTypes as $serviceType) {
            \App\Models\Reward::updateOrCreate(
                ['service_type_id' => $serviceType->id],
                [
                    'name'            => $serviceType->name,
                    'description'     => $serviceType->description ?: "Exclusive promo for {$serviceType->name} service.",
                    'points_cost'     => $serviceType->promo_points_cost ?? 100,
                    'is_active'       => true,
                ]
            );
            $count++;
        }

        $this->info("Successfully synchronized {$count} loyalty rewards.");
    }
}
