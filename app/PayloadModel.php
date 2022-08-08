<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayloadModel extends Model
{
    public function droneModel()
    {
        return $this->belongsToMany(DroneModel::class);
    }

    public function payloads()
    {
        return $this->hasMany(PayloadInventory::class);
    }
}
