<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DroneModel extends Model
{
    public function payloadModel()
    {
        return $this->belongsToMany(PayloadModel::class);
    }

    public function batteryModel()
    {
        return $this->hasOne(BatteryModel::class);
    }

    public function droneInventory()
    {
        return $this->hasMany(DroneInventory::class);
    }
}
