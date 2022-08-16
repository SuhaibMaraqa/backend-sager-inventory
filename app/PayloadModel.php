<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayloadModel extends Model
{
    protected $guarded = [];

    // public function droneModel()
    // {
    //     return $this->belongsToMany(DroneModel::class);
    // }
    public function droneModel()
    {
        return $this->belongsToMany(DroneModel::class, 'drone_payload_attachment', 'payload_id', 'drone_id');
    }

    public function attachDroneModel(DroneModel $droneModel)
    {
        return $this->droneModel()->save($droneModel);
    }

    public function payloads()
    {
        return $this->hasMany(PayloadInventory::class);
    }
}
