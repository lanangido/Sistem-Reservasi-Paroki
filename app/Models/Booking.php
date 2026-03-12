<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_time',
        'end_time',
        'purpose',
        'attendees',
        'status',
        'admin_note',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // TAMBAHKAN RELASI INI: Relasi Many-to-Many ke Asset
    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'asset_booking')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}