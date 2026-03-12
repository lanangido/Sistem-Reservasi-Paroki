<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rooms')->insert([
            [
                'name' => 'Aula Widya Graha Lt 1',
                'capacity' => 200,
                'description' => 'Fasilitas: AC, Proyektor, Sound System standar',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Rapat Dewan Paroki',
                'capacity' => 30,
                'description' => 'Fasilitas: AC, Meja Bundar, Whiteboard',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}