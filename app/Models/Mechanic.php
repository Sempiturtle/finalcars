<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'specialty',
        'phone',
    ];

    /**
     * Count the active (in progress) vehicles assigned to this mechanic.
     */
    public function inProgressCount(): int
    {
        return \App\Models\Vehicle::where('mechanic_name', $this->name)
            ->where('is_archived', false)
            ->where('status', 'in progress')
            ->count();
    }

    /**
     * Count the scheduled vehicles assigned to this mechanic.
     */
    public function scheduledCount(): int
    {
        return \App\Models\Vehicle::where('mechanic_name', $this->name)
            ->where('is_archived', false)
            ->where('status', 'scheduled')
            ->count();
    }

    /**
     * Check if the mechanic is currently available.
     * Available if they have:
     * - 0 vehicles in progress
     * - Less than 5 scheduled vehicles
     */
    public function isAvailable(): bool
    {
        return $this->inProgressCount() < 1 && $this->scheduledCount() < 5;
    }

    /**
     * Get the current active (in progress) vehicle assigned to this mechanic.
     */
    public function currentActiveVehicle()
    {
        return \App\Models\Vehicle::where('mechanic_name', $this->name)
            ->where('is_archived', false)
            ->where('status', 'in progress')
            ->first();
    }
}
