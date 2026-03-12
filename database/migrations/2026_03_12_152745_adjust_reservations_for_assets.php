<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat room_id menjadi nullable di tabel bookings dan assets
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->change();
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->change();
        });

        // 2. Buat tabel pivot untuk relasi Many-to-Many antara Booking dan Asset
        Schema::create('asset_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->integer('quantity'); // Jumlah barang yang dipinjam
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_booking');
        
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable(false)->change();
        });
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable(false)->change();
        });
    }
};