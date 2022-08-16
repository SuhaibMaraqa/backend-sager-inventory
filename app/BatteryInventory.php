<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatteryInventory extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batteryModel()
    {
        return $this->belongsTo(BatteryModel::class, 'battery_model_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
