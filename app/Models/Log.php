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
}