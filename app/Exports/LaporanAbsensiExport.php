<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LaporanAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $absensisData;
    protected $rowNumber = 0;

    public function __construct($absensisData)
    {
        // Ini adalah data $absensis yang SUDAH diproses dari controller
        $this->absensisData = $absensisData;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->absensisData;
    }

    /**
     * 1. PERUBAHAN: Header 'Kode Verifikasi' ditambahkan
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
            'Shift',
            'Kode Verifikasi', // <-- KOLOM BARU DITAMBAHKAN
            'Keterangan' 
        ];
    }

    /**
     * 2. PERUBAHAN: Logika 'Shift' dan 'Kode Verifikasi' diperbarui
     */
    public function map($records): array
    {
        $this->rowNumber++;

        // Ambil data penting dari grup
        $firstRecord = $records->first();
        $user = $firstRecord->user; // Relasi user
        $masuk = $records->firstWhere('tipe_absen', 'masuk');
        $pulang = $records->firstWhere('tipe_absen', 'pulang');

        // Ambil data user
        $departemen = $user ? $user->departement : '-';
        $nama = $user ? $user->name : 'User Dihapus';
        $badge_number = $user ? $user->badge_number : '-';

        // Ambil tanggal
        $tanggal = Carbon::parse($firstRecord->tanggal_waktu)->format('d/m/Y');

        // Hitung total jam
        $totalJam = '-';
        if ($masuk && $pulang) {
            $waktuMasuk = Carbon::parse($masuk->tanggal_waktu);
            $waktuPulang = Carbon::parse($pulang->tanggal_waktu);
            $totalJam = $waktuMasuk->diff($waktuPulang)->format('%H:%I:%S');
        }

        // --- (PERUBAHAN 1) Ambil JENIS Shift ---
        // Mengambil 'jenis_shift' dari relasi jamKerja, bukan 'nama_shift'
        $shift = $masuk ? ($masuk->jamKerja->jenis_shift ?? '-') : '-';

        // --- (LOGIKA BARU 2) Kode Verifikasi ---
        // Cek rekaman 'masuk' apakah ada foto DAN lokasi
        $kode_verifikasi = '-'; // Default (dianggap absen mesin)
        if ($masuk && !empty($masuk->foto) && !empty($masuk->lokasi)) {
            $kode_verifikasi = 'Online'; // Absen via HP (ada foto & lokasi)
        }

        // Ambil keterangan (yang sudah dihitung di controller)
        $keterangan = $records->status_hadir ?? 'Error'; 

        return [
            $this->rowNumber,
            $departemen,
            $nama,
            $badge_number,
            $tanggal,
            $masuk ? Carbon::parse($masuk->tanggal_waktu)->format('H:i') : '-',
            $pulang ? Carbon::parse($pulang->tanggal_waktu)->format('H:i') : '-',
            $totalJam,
            $shift, // <-- Data 'jenis_shift' akan tampil di sini
            $kode_verifikasi, // <-- Data 'Kode Verifikasi' akan tampil di sini
            $keterangan
        ];
    }
}