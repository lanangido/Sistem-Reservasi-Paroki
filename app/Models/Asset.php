<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = ['id'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'asset_booking')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}