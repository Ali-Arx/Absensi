<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lemburs';

    protected $fillable = [
        'user_id',
        'tanggal',
        'section',
        'jam_kerja_id',
        'jam_masuk',
        'jam_keluar',
        'departemen',
        'nama_karyawan',
        'nama_atasan',
        'job_description',
        'paraf',
        'status',
        'disetujui_oleh',
        'tanggal_disetujui',
    ];

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'jam_kerja_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
