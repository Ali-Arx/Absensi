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
                'name' => 'Yeni',
                'badge_number' => 'HR002',
                'email' => 'yeni@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'HR',
                'departement' => "Office",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nadirman',
                'badge_number' => 'DIR002',
                'email' => 'nadirman@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Direktur',
                'departement' => "Office", 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Defri',
                'badge_number' => 'ATASAN002',
                'email' => 'defri@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Atasan',
                'departement' => "Sales", 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zainuddin',
                'badge_number' => 'ATASAN003',
                'email' => 'zainuddin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Atasan',
                'departement' => "Production", 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Darwin',
                'badge_number' => 'ATASAN004',
                'email' => 'darwin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Atasan',
                'departement' => "Production", 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['name' => 'Rafly',
                'badge_number' => 'ATASAN005',
                'email' => 'rafly@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Atasan',
                'departement' => "Engineering", 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
