<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DroneInventory extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function droneModel(){
        return $this->belongsTo(DroneModel::class, 'drone_model_id');
    }
}
