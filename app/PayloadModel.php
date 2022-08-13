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
        return $this->belongsToMany(DroneModel::class, 'drone_payload_attachment', 'drone_id', 'payload_id');
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
