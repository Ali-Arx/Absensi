<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Lembur;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggal = Carbon::today()->format('Y-m-d');
        $users = User::all();

        $data = $users->map(function ($user) use ($tanggal) {
            // 1️⃣ Cek CUTI
            $cuti = Cuti::where('user_id', $user->id)
                ->where('status_pengajuan', 'disetujui')
                ->whereDate('tgl_mulai', '<=', $tanggal)
                ->whereDate('tgl_selesai', '>=', $tanggal)
                ->first();

            if ($cuti) {
                return [
                    'name'        => $user->name,
                    'departement' => $user->departement,
                    'tanggal'     => $tanggal,
                    'jam_masuk'   => '-',
                    'jam_pulang'  => '-',
                    'status'      => 'Cuti',
                ];
            }

            // 2️⃣ Cek LEMBUR
            $lembur = Lembur::where('user_id', $user->id)
                ->where('status_pengajuan', 'disetujui')
                ->whereDate('tgl_pengajuan', $tanggal)
                ->first();

            if ($lembur) {
                return [
                    'name'        => $user->name,
                    'departement' => $user->departement,
                    'tanggal'     => $tanggal,
                    'jam_masuk'   => $lembur->jam_mulai ?? '-',
                    'jam_pulang'  => $lembur->jam_selesai ?? '-',
                    'status'      => 'Lembur',
                ];
            }

            // 3️⃣ Cek ABSENSI HARI INI (tipe 'masuk')
            $absen = Absensi::with('jamKerja')
                ->where('user_id', $user->id)
                ->where('tipe_absen', 'masuk')
                ->whereDate('tanggal_waktu', $tanggal)
                ->first();

            if ($absen) {
                // ====== LOGIKA HADIR / TERLAMBAT / TIDAK HADIR ======
                $jamKerja = $absen->jamKerja;

                if ($jamKerja) {
                    $jamMasukNormalStr  = $jamKerja->jam_masuk ?? '08:00:00';
                    $jamKeluarNormalStr = $jamKerja->jam_keluar ?? '17:00:00';

                    $tanggalHariIni = Carbon::parse($absen->tanggal_waktu)->format('Y-m-d');
                    $jamMasukNormal   = Carbon::parse("$tanggalHariIni $jamMasukNormalStr");
                    $jamKeluarNormal  = Carbon::parse("$tanggalHariIni $jamKeluarNormalStr");
                    $jamAbsen         = Carbon::parse($absen->tanggal_waktu);

                    // Tambahkan toleransi 10 menit
                    $jamMasukToleransi = $jamMasukNormal->copy()->addMinutes(10);

                    if ($jamAbsen->lt($jamMasukToleransi)) {
                        $keterangan = 'Hadir';
                    } elseif ($jamAbsen->between($jamMasukToleransi, $jamKeluarNormal)) {
                        $keterangan = 'Hadir (Terlambat)';
                    } else {
                        $keterangan = 'Tidak Hadir';
                    }
                } else {
                    // Jika absensi tidak punya relasi jam kerja
                    $keterangan = 'Hadir';
                }

                return [
                    'name'        => $user->name,
                    'departement' => $user->departement,
                    'tanggal'     => $tanggal,
                    'jam_masuk'   => $absen->jam_masuk ?? '-',
                    'jam_pulang'  => $absen->jam_pulang ?? '-',
                    'status'      => $keterangan,
                ];
            }

            // 4️⃣ Jika tidak ada semua → Tidak Hadir
            return [
                'name'        => $user->name,
                'departement' => $user->departement,
                'tanggal'     => $tanggal,
                'jam_masuk'   => '-',
                'jam_pulang'  => '-',
                'status'      => 'Tidak Hadir',
            ];
        });

        return view('dashboard.hr', compact('data'));
    }

    public function atasan()
    {
        return view('dashboard.atasan');
    }
}
