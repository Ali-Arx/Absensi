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
     * Definisikan Header
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
            'Keterangan' // Ini adalah status (Hadir, Terlambat, Izin, dll)
        ];
    }

    /**
     * Petakan data untuk setiap baris
     * $records adalah grup absensi (masuk/pulang) untuk 1 user pada 1 hari
     */
    public function map($records): array
    {
        $this->rowNumber++;

        // Ambil data penting dari grup
        $firstRecord = $records->first();
        $user = $firstRecord->user; // Relasi user
        $masuk = $records->firstWhere('tipe_absen', 'masuk');
        $pulang = $records->firstWhere('tipe_absen', 'pulang');

        // Ambil data user (pastikan user ada)
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

        // Ambil shift
        $shift = $masuk ? ($masuk->jamKerja->nama_shift ?? '-') : '-';

        // Ambil keterangan (yang sudah dihitung di controller)
        // 'status_hadir' adalah properti yg kita buat di fungsi data()
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
            $shift,
            $keterangan
        ];
    }
}