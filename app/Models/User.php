<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'address',
        'loyalty_points',
    ];

    public function calculateActivityLevel()
    {
        $lastService = $this->vehicles()->with(['serviceLogs' => function($query) {
            $query->latest('service_date');
        }])->get()->flatMap->serviceLogs->sortByDesc('service_date')->first();

        if (!$lastService) return 'Inactive';
        
        $daysSince = \Carbon\Carbon::parse($lastService->service_date)->diffInDays(now());
        
        if ($daysSince <= 90) return 'Active';
        if ($daysSince <= 180) return 'Regular';
        return 'Inactive';
    }

    public function recalculateLoyaltyPoints()
    {
        $serviceLogs = $this->vehicles()->with(['serviceLogs' => function($q) {
            $q->where('status', 'completed');
        }])->get()->flatMap(function($vehicle) {
            return $vehicle->serviceLogs;
        });

        $earnedPoints = 0;
        foreach ($serviceLogs as $log) {
            $earnedPoints += $log->points_earned;
        }

        // Subtract spent points from claimed rewards
        $spentPoints = $this->rewards()->sum('points_cost');
        
        $finalPoints = max(0, $earnedPoints - $spentPoints);
        
        $this->update(['loyalty_points' => $finalPoints]);
    }

    /**
     * Calculate how many points the user has earned specifically from a given service type.
     */
    public function pointsForServiceType(string $serviceTypeName): int
    {
        $earned = (int) $this->vehicles()
            ->with(['serviceLogs' => fn($q) => $q->where('status', 'completed')])
            ->get()
            ->flatMap(function($vehicle) {
                return $vehicle->serviceLogs;
            })
            ->filter(function($log) use ($serviceTypeName) {
                return strtolower(trim($log->service_type)) === strtolower(trim($serviceTypeName));
            })
            ->sum(function($log) {
                return $log->points_earned;
            });

        $spent = (int) $this->rewards()
            ->whereHas('serviceType', function($q) use ($serviceTypeName) {
                $q->where('name', $serviceTypeName);
            })
            ->sum('points_cost');

        return max(0, $earned - $spent);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class)->withTimestamps()->withPivot('claimed_at');
    }

    public function totalSpentPoints(): int
    {
        return $this->rewards()->sum('points_cost') ?? 0;
    }

    public function availablePoints(): int
    {
        return max(0, $this->loyalty_points - $this->totalSpentPoints());
    }

    public function unreadMessagesCount(): int
    {
        return ChatMessage::where('receiver_id', $this->id)->whereNull('read_at')->count();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
