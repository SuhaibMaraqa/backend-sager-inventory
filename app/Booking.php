<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payloadInventory()
    {
        return $this->belongsTo(PayloadInventory::class);
    }

    public function droneInventory()
    {
        return $this->belongsTo(DroneInventory::class);
    }

    public function batteryInventory()
    {
        return $this->belongsTo(BatteryInventory::class);
    }
}
