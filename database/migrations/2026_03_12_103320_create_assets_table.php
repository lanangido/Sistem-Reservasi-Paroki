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
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('asset_name'); // Nama Barang (Contoh: Proyektor Aula)
        $table->string('asset_code')->unique(); // Kode Unik (Contoh: PM-AUL-001)
        
        // Menghubungkan aset ke ruangan tertentu
        $table->foreignId('room_id')->constrained()->onDelete('cascade');
        
        $table->integer('stock_total'); // Jumlah total barang yang dimiliki
        $table->integer('stock_available'); // Barang yang siap dipinjam
        
        // Kondisi barang: Bagus, Rusak, atau Sedang Diperbaiki
        $table->enum('condition', ['good', 'broken', 'maintenance'])->default('good');
        
        $table->text('description')->nullable(); // Catatan tambahan (merk, tahun beli, dll)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
