<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Romo Paroki (Super Admin)',
                'email' => 'admin@paroki.com',
                'phone_number' => '628111111111',
                'password' => Hash::make('password123'), // Password default
                'lingkungan' => 'Pusat',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas Sekretariat',
                'email' => 'sekretariat@paroki.com',
                'phone_number' => '628222222222',
                'password' => Hash::make('password123'),
                'lingkungan' => 'Pusat',
                'role' => 'sekretariat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Umat Pemohon',
                'email' => 'umat@paroki.com',
                'phone_number' => '628333333333',
                'password' => Hash::make('password123'),
                'lingkungan' => 'Lingkungan St. Yohanes',
                'role' => 'umat',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}