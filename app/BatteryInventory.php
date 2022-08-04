<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatteryInventory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batteryModel()
    {
        return $this->belongsTo(BatteryModel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
