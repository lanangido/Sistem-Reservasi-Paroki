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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        
        // Foreign Keys (Relasi ke tabel users dan rooms)
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
        
        // Detail Acara
        $table->dateTime('start_time'); // Waktu mulai
        $table->dateTime('end_time');   // Waktu selesai
        $table->string('purpose', 255); // Tujuan kegiatan
        $table->integer('attendees');   // Estimasi peserta
        
        // Status dan Catatan
        $table->enum('status', ['pending', 'approved', 'rejected', 'canceled'])->default('pending');
        $table->text('admin_note')->nullable(); // Catatan admin (alasan penolakan, dll)
        
        $table->timestamps(); // Otomatis membuat created_at (KUNCI UTAMA algoritma FIFO) dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
