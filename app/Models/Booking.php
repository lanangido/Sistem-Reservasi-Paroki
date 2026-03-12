<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Memberikan izin kolom mana saja yang boleh diisi dari form (Mass Assignment)
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
    // Relasi: Satu Booking ini meminjam Ruangan apa?
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi: Satu Booking ini diajukan oleh User siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}