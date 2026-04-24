<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ServiceType;
use App\Models\Reward;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $serviceTypes = ServiceType::all();
        foreach ($serviceTypes as $serviceType) {
            Reward::firstOrCreate(
                ['service_type_id' => $serviceType->id],
                [
                    'name'            => $serviceType->name,
                    'description'     => $serviceType->description ?: "Exclusive promo for {$serviceType->name} service.",
                    'points_cost'     => $serviceType->promo_points_cost ?? 100,
                    'is_active'       => true,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to delete rewards on rollback
    }
};
