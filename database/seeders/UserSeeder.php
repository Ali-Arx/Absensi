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
                'name' => 'Admin HR',
                'badge_number' => 'HR001',
                'email' => 'hr@example.com',
                'password' => Hash::make('password'),
                'role' => 'hr',
                'departement' => "HR",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Direktur Utama',
                'badge_number' => 'DIR001',
                'email' => 'direktur@example.com',
                'password' => Hash::make('password'),
                'role' => 'direktur',
                'departement' => "Finance", // Finance
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Atasan Produksi',
                'badge_number' => 'ATASAN001',
                'email' => 'atasan@example.com',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'departement' => "Produksi", // Produksi
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karyawan IT',
                'badge_number' => 'KAR001',
                'email' => 'karyawan@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'departement' => "IT", // IT
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
