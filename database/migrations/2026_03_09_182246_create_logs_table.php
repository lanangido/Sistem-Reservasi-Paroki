<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('logs', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke booking mana yang diubah, dan siapa admin yang mengubahnya
        $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
        $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
        
        // Aksi yang dilakukan (approve, reject, dll)
        $table->string('action'); 
        
        $table->timestamps(); // Otomatis membuat kolom waktu kejadian
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
