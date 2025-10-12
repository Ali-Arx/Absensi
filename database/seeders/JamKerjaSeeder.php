<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamKerjaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jam_kerjas')->insert([
            [
                'jenis_shift' => 'Shift Pagi',
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '16:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_shift' => 'Shift Malam',
                'jam_masuk' => '16:00:00',
                'jam_keluar' => '00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
