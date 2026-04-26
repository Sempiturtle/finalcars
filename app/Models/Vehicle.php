<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plate_number',
        'make',
        'model',
        'year',
        'color',
        'owner_name',
        'mechanic_name',
        'next_service_date',
        'registration_date',
        'status',
        'services',
        'total_cost',
        'last_ai_message',
        'last_notification_at',
    ];

    protected $casts = [
        'next_service_date' => 'date',
        'registration_date' => 'date',
        'services' => 'array',
        'total_cost' => 'decimal:2',
        'last_notification_at' => 'datetime',
    ];

    /**
     * Scope for vehicles that are 5 or more days overdue.
     */
    public function scopeCriticalOverdue($query)
    {
        return $query->where('next_service_date', '<=', now()->subDays(5))
                     ->where('status', '!=', 'in progress');
    }

    /**
     * Check if the vehicle is 5 or more days overdue.
     */
    public function isCriticalOverdue()
    {
        if (!$this->next_service_date) return false;
        return $this->next_service_date->lte(now()->subDays(5));
    }
    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::created(function ($vehicle) {
            $vehicle->syncServiceLogs();
            
            // Notify owner of new vehicle registration
            if ($vehicle->owner) {
                $vehicle->owner->notify(new \App\Notifications\SystemNotification(
                    'New Vehicle Registered',
                    "A new {$vehicle->make} {$vehicle->model} (Plate: {$vehicle->plate_number}) has been added to your account.",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    route('customer.dashboard')
                ));
            }
        });

        static::updated(function ($vehicle) {
            // Always sync — the status column must always reflect calculated_status
            $vehicle->syncServiceLogs();

            // Notify owner of vehicle reassignment
            if ($vehicle->isDirty('user_id') && $vehicle->owner) {
                $vehicle->owner->notify(new \App\Notifications\SystemNotification(
                    'Vehicle Reassigned To You',
                    "The {$vehicle->make} {$vehicle->model} (Plate: {$vehicle->plate_number}) is now under your management.",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
                    route('customer.dashboard')
                ));
            }
        });
    }

    /**
     * Calculate the real status based on individual services.
     */
    public function getCalculatedStatusAttribute()
    {
        if ($this->status === 'inactive') {
            return 'inactive';
        }

        if (!is_array($this->services) || empty($this->services)) {
            // Check if overdue based on date even if no services are listed
            if ($this->next_service_date && $this->next_service_date->isToday()) {
                return 'due today';
            }
            if ($this->next_service_date && $this->next_service_date->lt(now()->startOfDay())) {
                return 'overdue';
            }
            return $this->status;
        }

        $services = collect(is_array($this->services) ? $this->services : []);
        $total = $services->count();
        
        // Use case-insensitive check for statuses
        $completedCount = $services->filter(fn($s) => strtolower($s['status'] ?? '') === 'completed')->count();
        $inProgressCount = $services->filter(fn($s) => strtolower($s['status'] ?? '') === 'in progress')->count();

        // 1. In Progress takes absolute priority.
        if ($inProgressCount > 0 || ($completedCount > 0 && $completedCount < $total)) {
            return 'in progress';
        }

        // 2. Due Today: If NOT in progress and the date is EXACTLY today.
        if ($this->next_service_date && $this->next_service_date->isToday() && ($total === 0 || $completedCount < $total)) {
            return 'due today';
        }

        // 3. Overdue: Only if NOT in progress and the date has strictly passed.
        if ($this->next_service_date && $this->next_service_date->lt(now()->startOfDay()) && ($total === 0 || $completedCount < $total)) {
            return 'overdue';
        }

        if ($total > 0 && $completedCount === $total) return 'completed';
        
        return 'scheduled';
    }

    /**
     * Get detailed progress breakdown.
     */
    public function getServiceProgressAttribute()
    {
        $services = is_array($this->services) ? $this->services : [];
        $total = count($services);
        $completed = collect($services)->where('status', 'completed')->count();
        
        return [
            'completed' => $completed,
            'total' => $total,
            'percent' => $total > 0 ? round(($completed / $total) * 100) : 0
        ];
    }

    /**
     * Synchronize the services JSON array with the ServiceLog table.
     */
    public function syncServiceLogs()
    {
        if (!is_array($this->services)) {
            return;
        }

        $isAdmin = (auth()->check() && auth()->user()->isAdmin());
        
        // Keep track of which logs we've already matched in this pass 
        // to handle multiple services of the same type correctly.
        $matchedLogIds = [];

        foreach ($this->services as $service) {
            $type = $service['type'] ?? 'N/A';
            $targetStatus = $service['status'] ?? ($isAdmin && $this->status === 'completed' ? 'completed' : 'scheduled');
            
            // Try to find an existing log that matches this type, is not yet completed,
            // and hasn't been matched yet in this loop.
            $log = $this->serviceLogs()
                ->where('service_type', $type)
                ->where('status', '!=', 'completed')
                ->whereNotIn('id', $matchedLogIds)
                ->first();

            if ($log) {
                $matchedLogIds[] = $log->id;
                $oldStatus = $log->status;
                
                $log->update([
                    'status' => $targetStatus,
                    'cost' => $service['cost'] ?? $log->cost,
                    'service_mode' => $service['mode'] ?? $log->service_mode,
                    'notes' => $service['notes'] ?? $log->notes,
                    'service_date' => $service['date'] ?? $log->service_date,
                ]);

                // If just marked as completed, record WHO did it
                if ($oldStatus !== 'completed' && $targetStatus === 'completed' && auth()->check()) {
                    $log->update(['completed_by_id' => auth()->id()]);
                }
            } else {
                // If no pending log matches, check if we should create a new one.
                $alreadyCompletedCount = $this->serviceLogs()
                    ->where('service_type', $type)
                    ->where('status', 'completed')
                    ->whereNotIn('id', $matchedLogIds)
                    ->count();
                
                // If there are more entries in JSON than in the table, we need to create one.
                $jsonCountOfThisType = collect($this->services)->where('type', $type)->count();
                $tableCountOfThisType = $this->serviceLogs()->where('service_type', $type)->count();

                if ($tableCountOfThisType < $jsonCountOfThisType) {
                    $newLog = $this->serviceLogs()->create([
                        'service_type' => $type,
                        'service_mode' => $service['mode'] ?? 'Walk-in',
                        'cost' => $service['cost'] ?? 0,
                        'status' => $targetStatus,
                        'service_date' => $service['date'] ?? now(),
                        'mechanic_name' => 'Pending Assignment',
                        'notes' => $service['notes'] ?? null,
                        'completed_by_id' => ($targetStatus === 'completed' && auth()->check()) ? auth()->id() : null,
                    ]);
                    $matchedLogIds[] = $newLog->id;
                }
            }
        }

        if ($this->owner) {
            $this->owner->recalculateLoyaltyPoints();
        }

        // 1. Calculate the new Next Service Date first
        $pendingServices = collect($this->services)->where('status', '!=', 'completed');
        $earliestDate = $pendingServices->pluck('date')->filter(fn($d) => !empty($d))->min();
        
        $updateData = [];
        $currentDateString = $this->next_service_date ? $this->next_service_date->format('Y-m-d') : null;
        
        if ($earliestDate) {
            $updateData['next_service_date'] = $earliestDate;
            // Temporarily update the instance so calculated_status uses the NEW date
            $this->next_service_date = \Carbon\Carbon::parse($earliestDate);
        }

        // 2. Now calculate status based on the updated date
        $newStatus = $this->calculated_status;
        
        // CRITICAL: 'due today' is for UI only. Map back to 'scheduled' for DB persistence.
        $dbStatus = ($newStatus === 'due today') ? 'scheduled' : $newStatus;
        $updateData['status'] = $dbStatus;
        
        // 3. ALWAYS apply changes to DB — never skip, to prevent stale status
        \Illuminate\Support\Facades\DB::table('vehicles')
            ->where('id', $this->id)
            ->update($updateData);
        
        $this->status = $dbStatus;
    }
}
