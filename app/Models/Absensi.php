<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Absensi extends Model
{
    use HasFactory;

    /**
     * Jika nama tabel kamu plural "absensis", pastikan ini sesuai.
     * Kalau tabelmu bernama "absensi" ubah menjadi protected $table = 'absensi';
     */
    protected $table = 'absensis';

    protected $fillable = [
        'id',        
        'user_id',
        'jam_kerja_id',
        'tanggal_waktu',
        'tipe_absen',
        'foto',
        'lokasi',
       
    ];

    /**
     * Cast ke datetime supaya gampang format
     */
    protected $casts = [
        'tanggal_waktu' => 'datetime',
        'tgl_jam_masuk' => 'datetime',
        'tgl_jam_keluar' => 'datetime',
    ];

    /**
     * Relasi ke user (pastikan model User ada di App\Models\User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'jam_kerja_id');
    }

    /**
     * Optional: accessor helper supaya di blade bisa panggil $item->tanggal / jam_masuk / jam_pulang
     */
    public function getTanggalAttribute()
    {
        return $this->tgl_jam_masuk ? $this->tgl_jam_masuk->format('Y-m-d') : null;
    }

    public function getJamMasukAttribute()
    {
        return $this->tgl_jam_masuk ? $this->tgl_jam_masuk->format('H:i') : null;
    }

    public function getJamPulangAttribute()
    {
        return $this->tgl_jam_keluar ? $this->tgl_jam_keluar->format('H:i') : null;
    }
}
