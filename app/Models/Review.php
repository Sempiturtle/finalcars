<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_log_id',
        'vehicle_id',
        'rating',
        'comment',
        'is_public',
        'admin_reply',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceLog(): BelongsTo
    {
        return $this->belongsTo(ServiceLog::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
