<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'phone_number',
        'sid',
        'status',
        'tts_content',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
