<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayloadInventory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payloadModel()
    {
        return $this->belongsTo(PayloadModel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
