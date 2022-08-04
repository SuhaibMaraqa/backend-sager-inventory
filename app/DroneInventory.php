<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DroneInventory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsTo(DroneModel::class);
    }
}
