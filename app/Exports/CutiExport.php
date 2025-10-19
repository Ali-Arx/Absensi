<?php

namespace App\Exports;

use App\Models\Cuti; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class CutiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $cutis;
    protected $rowNumber = 0;

    public function __construct($cutis)
    {
        $this->cutis = $cutis;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->cutis;
    }

    /**
     * 1. Header 'Nama Atasan' ditambahkan
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Karyawan',
            'No ID Karyawan',
            'Departemen',
            'Tanggal Pengajuan',
            'Jenis Cuti',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status Pengajuan',
            'Tanggal Status', 
            'Nama Atasan', // <-- KOLOM BARU DITAMBAHKAN
            'Keterangan'
        ];
    }

    /**
     * 2. Logika 'map' diperbarui
     */
    public function map($cuti): array
    {
        $this->rowNumber++;
        $user = $cuti->user; // Karyawan yang mengajukan
        
        // --- (Logika Status Terjemahan) ---
        $status_terjemahan = match ($cuti->status_pengajuan) {
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($cuti->status_pengajuan)
        };

        // --- (Logika Tanggal Status) ---
        $tanggal_status = '-';
        if ($cuti->status_pengajuan !== 'menunggu') {
            $tanggal_status = Carbon::parse($cuti->updated_at)->format('d/m/Y H:i');
        }

        // --- (Logika Baru untuk Nama Atasan) ---
        // Mengambil dari relasi approver() yang sudah kita muat
        $approver = $cuti->approver;
        $nama_atasan = $approver ? $approver->name : '-'; // Ambil 'name' dari approver

        return [
            $this->rowNumber,
            $user ? $user->name : 'User Dihapus',
            $user ? $user->badge_number : '-',
            $user ? $user->departement : '-',
            Carbon::parse($cuti->tgl_pengajuan)->format('d/m/Y'),
            $cuti->jenis_cuti ?? '-',
            Carbon::parse($cuti->tgl_mulai)->format('d/m/Y'),
            Carbon::parse($cuti->tgl_selesai)->format('d/m/Y'),
            $status_terjemahan,
            $tanggal_status, 
            
            $nama_atasan, // <-- DATA ATASAN DIAMBIL DARI $cuti->approver
            
            $cuti->alasan ?? '-' 
        ];
    }
}