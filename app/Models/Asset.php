<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['asset_name', 'asset_code', 'room_id', 'stock_total', 'stock_available', 'condition', 'description'];

public function room()
{
    return $this->belongsTo(Room::class);
}
}
