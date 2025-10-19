<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AbsensiExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping
{
    protected $groupedData;
    protected $user;
    protected $rowNumber = 0; 

    public function __construct($groupedData, $user)
    {
        $this->groupedData = $groupedData;
        $this->user = $user;
    }

    public function collection()
    {
        return $this->groupedData;
    }

    /**
     * Header (Tetap Sama)
     */
    public function headings(): array
    {
        return [
            'No',
            'Departemen',
            'Nama',
            'No ID',
            'Tanggal',
            'Waktu Masuk',
            'Waktu Pulang',
            'Total Jam',
            'Shift', // Header tetap 'Shift'
            'Kode Verifikasi', 
            'Keterangan'
        ];
    }

    /**
     * Pemetaan data (Logika Shift Diperbarui)
     */
    public function map($records): array
    {
        $this->rowNumber++;
        
        $masuk = $records->firstWhere('tipe_absen', 'masuk');
        $pulang = $records->firstWhere('tipe_absen', 'pulang');

        $totalJam = '-';
        if ($masuk && $pulang) {
            $waktuMasuk = Carbon::parse($masuk->tanggal_waktu);
            $waktuPulang = Carbon::parse($pulang->tanggal_waktu);
            $totalJam = $waktuMasuk->diff($waktuPulang)->format('%H:%I:%S');
        }

        $keterangan = 'Tidak Hadir';
        if ($masuk && isset($masuk->keterangan_dinamis)) {
            $keterangan = $masuk->keterangan_dinamis;
        } elseif ($pulang && !$masuk) {
            $keterangan = 'Data Tidak Lengkap';
        }
        
        $tanggal = Carbon::parse($records->first()->tanggal_waktu)->format('d/m/Y');
        
        $kode_verifikasi = '-'; 
        if ($masuk && !empty($masuk->foto) && !empty($masuk->lokasi)) {
            $kode_verifikasi = 'Online'; 
        }

        // --- INI PERUBAHANNYA ---
        // Mengambil 'jenis_shift' dari relasi jamKerja, bukan 'nama_shift'
        $shift = $masuk ? ($masuk->jamKerja->jenis_shift ?? '-') : '-';
        // -------------------------
        
        return [
            $this->rowNumber,
            $this->user->departement ?? '-',
            $this->user->name,
            $this->user->badge_number ?? '-',
            $tanggal,
            $masuk ? Carbon::parse($masuk->tanggal_waktu)->format('H:i') : '-', 
            $pulang ? Carbon::parse($pulang->tanggal_waktu)->format('H:i') : '-',
            $totalJam,
            $shift, // <-- Data 'jenis_shift' akan tampil di sini
            $kode_verifikasi, 
            $keterangan
        ];
    }
}