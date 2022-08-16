<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayloadInventory extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payloadModel()
    {
        return $this->belongsTo(PayloadModel::class, 'payload_model_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
