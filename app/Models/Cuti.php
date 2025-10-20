<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cuti extends Model
{
    protected $table = 'cutis';
    protected $fillable = [
        'id',
        'user_id',
        'approver_id',
        'tgl_pengajuan',
        'tgl_mulai',
        'tgl_selesai',
        'jenis_cuti',
        'alasan',
        'tanda_tangan',
        'status_pengajuan',
        'tgl_status',
        'komentar',
        'tanda_tangan_approval',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

