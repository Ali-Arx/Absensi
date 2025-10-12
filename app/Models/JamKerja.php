<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    protected $table = 'jam_kerjas';
    protected $fillable = [
        'id',
        'jenis_shift',
        'jam_masuk',
        'jam_keluar',
    ];
}
