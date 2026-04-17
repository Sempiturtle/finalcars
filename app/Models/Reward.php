<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'points_cost',
        'is_active',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot('claimed_at');
    }
}
