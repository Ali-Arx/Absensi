<?php

namespace App\Exports;

// Impor yang tidak perlu (WithColumnFormatting, Storage) telah dihapus
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
     * 1. Header telah diperbarui (Lokasi/Foto dihapus)
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
            // 'Lokasi Masuk',  <- Dihapus
            // 'Foto Masuk',    <- Dihapus
            'Waktu Pulang',
            // 'Lokasi Pulang', <- Dihapus
            // 'Foto Pulang',   <- Dihapus
            'Total Jam',
            'Shift',
            'Keterangan'
        ];
    }

    /**
     * 2. Pemetaan data telah diperbarui (Lokasi/Foto dihapus)
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
        
        $shift = $masuk ? ($masuk->jamKerja->nama_shift ?? '-') : '-';
        $tanggal = Carbon::parse($records->first()->tanggal_waktu)->format('d/m/Y');
        
        return [
            $this->rowNumber,
            $this->user->departement ?? '-',
            $this->user->name,
            $this->user->badge_number ?? '-',
            $tanggal,
            
            // Data Masuk
            $masuk ? Carbon::parse($masuk->tanggal_waktu)->format('H:i') : '-', 
            // Data Lokasi/Foto dihapus
            
            // Data Pulang
            $pulang ? Carbon::parse($pulang->tanggal_waktu)->format('H:i') : '-',
            // Data Lokasi/Foto dihapus

            // Data Lainnya
            $totalJam,
            $shift,
            $keterangan
        ];
    }

    // 3. Method helper (createMapsLink, createHyperlink) telah dihapus
    // 4. Method columnFormats() telah dihapus
}