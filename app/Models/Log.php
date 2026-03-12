<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    // Memberikan izin untuk pencatatan Jejak Audit (Audit Trail)
    protected $fillable = [
        'booking_id',
        'actor_id',
        'action',
    ];

    /**
     * Relasi ke tabel users (Aktor)
     * Untuk mengetahui Admin/Sekretariat siapa yang melakukan klik "Setujui" / "Selesai"
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Relasi ke tabel bookings
     * Untuk mengetahui pengajuan jadwal mana yang diubah statusnya
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}