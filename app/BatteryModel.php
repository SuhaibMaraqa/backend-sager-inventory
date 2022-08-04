<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatteryModel extends Model
{
    public function droneModel()
    {
        return $this->belongsTo(DroneModel::class, 'drone_model_id');
    }

    public function batteryInventory()
    {
        return $this->hasMany(BatteryInventory::class, 'battery_model_id');
    }
}
