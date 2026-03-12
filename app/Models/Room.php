<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Beri izin kolom ini untuk diisi lewat form CRUD nantinya
    protected $fillable = [
        'name',
        'capacity',
        'description',
        'image',
        'is_active',
    ];

    public function assets()
{
    return $this->hasMany(Asset::class);
}
}