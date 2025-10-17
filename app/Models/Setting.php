<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'jam_masuk_default',
        'jam_pulang_default',
        'durasi_istirahat',
        'maks_cuti_tahun',
        'maks_cuti_bulan',
        'cuti_minimal_sebelum_pengajuan',
        'password_min_8',
        'selfie_absensi',
        'verifikasi_gps',
        'export_mingguan',
    ];
}
