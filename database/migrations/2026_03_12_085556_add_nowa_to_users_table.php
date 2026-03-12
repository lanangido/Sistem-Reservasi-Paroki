<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom no_wa sudah ada
            if (!Schema::hasColumn('users', 'no_wa')) {
                $table->string('no_wa')->nullable()->after('lingkungan');
            }
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_wa');
        });
    }
};
