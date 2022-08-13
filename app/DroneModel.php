<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DroneModel extends Model
{
    protected $guarded = [];

    // public function payloadModel()
    // {
    //     return $this->belongsToMany(PayloadModel::class);
    // }

    public function payloadModel()
    {
        return $this->belongsToMany(payloadModel::class, 'drone_payload_attachment', 'drone_id', 'payload_id');
    }

    public function attachPayloadModel(PayloadModel $payloadModel)
    {
        return $this->payloadModel()->save($payloadModel);
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
