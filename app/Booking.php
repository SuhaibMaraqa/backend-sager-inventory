<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payloadInventory()
    {
        return $this->belongsTo(PayloadInventory::class, 'payload_id');
    }

    public function droneInventory()
    {
        return $this->belongsTo(DroneInventory::class, 'drone_id');
    }

    public function batteryInventory()
    {
        return $this->belongsTo(BatteryInventory::class, 'battery_id');
    }
}
