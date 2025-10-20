<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\User;
use App\Models\JamKerja;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    /**
     * @param Collection $rows
     */
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // 1. Temukan User
                static $userCache = [];
                $badge_number = $row['no_id'];
                if (!isset($userCache[$badge_number])) {
                    $userCache[$badge_number] = User::where('badge_number', $badge_number)->first();
                }
                $user = $userCache[$badge_number];

                if (!$user) {
                    continue;
                }

                // 2. Temukan Shift
                static $jamKerjaCache = [];
                $shiftNama = $row['shift'] ?? '-';
                $jamKerjaId = null;
                if ($shiftNama !== '-') {
                    if (!isset($jamKerjaCache[$shiftNama])) {
                        $jamKerjaCache[$shiftNama] = JamKerja::where('jenis_shift', $shiftNama)->first();
                    }
                    $jamKerja = $jamKerjaCache[$shiftNama];
                    $jamKerjaId = $jamKerja ? $jamKerja->id : null;
                }

                // 3. Parse Tanggal
                try {
                    $tanggal = Carbon::createFromFormat('d/m/Y', $row['tanggal']);
                } catch (\Exception $e) {
                    continue;
                }
                // $tanggalString adalah 'YYYY-MM-DD'
                $tanggalString = $tanggal->format('Y-m-d');

                // 4. Ambil Waktu
                $waktuMasuk = $row['waktu_masuk'] ?? '-';
                $waktuPulang = $row['waktu_pulang'] ?? '-';

                $kodeVerifikasi = $row['kode_verifikasi'] ?? '-';
                $lokasi_impor = null;
                $foto_impor = null;

                if (!empty($kodeVerifikasi) && $kodeVerifikasi !== '-') {
                    $lokasi_impor = $kodeVerifikasi;
                    $foto_impor = $kodeVerifikasi;
                }
                // 6. Logika Impor (Mengabaikan 'Keterangan')

                if (!empty($waktuMasuk) && $waktuMasuk !== '-') {
                    // Cek dulu apakah data sudah ada
                    $existingAbsen = Absensi::where('user_id', $user->id)
                        ->where('tipe_absen', 'masuk')
                        ->whereDate('tanggal_waktu', $tanggalString)
                        ->first();

                    // HANYA CREATE JIKA BELUM ADA
                    if (!$existingAbsen) {
                        $data = [
                            'tanggal_waktu' => $tanggal->copy()->setTimeFromTimeString($waktuMasuk),
                            'jam_kerja_id' => $jamKerjaId,
                            'lokasi' => $lokasi_impor,
                            'foto' => $foto_impor,
                            'user_id' => $user->id,
                            'tipe_absen' => 'masuk'
                        ];
                        Absensi::create($data);
                    }
                    // Jika $existingAbsen ada, JANGAN LAKUKAN APA-APA (SKIP)
                }
                if (!empty($waktuPulang) && $waktuPulang !== '-') {
                    // Cek dulu apakah data sudah ada
                    $existingAbsen = Absensi::where('user_id', $user->id)
                        ->where('tipe_absen', 'pulang')
                        ->whereDate('tanggal_waktu', $tanggalString)
                        ->first();

                    // HANYA CREATE JIKA BELUM ADA
                    if (!$existingAbsen) {
                        $data = [
                            'tanggal_waktu' => $tanggal->copy()->setTimeFromTimeString($waktuPulang),
                            'jam_kerja_id' => $jamKerjaId,
                            'lokasi' => $lokasi_impor,
                            'foto' => $foto_impor,
                            'user_id' => $user->id,
                            'tipe_absen' => 'pulang'
                        ];
                        Absensi::create($data);
                    }
                    // Jika $existingAbsen ada, JANGAN LAKUKAN APA-APA (SKIP)
                }
                // --- (SELESAI) PERBAIKAN UNTUK WAKTU PULANG ---
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'no_id' => 'required|string|exists:users,badge_number',
            'tanggal' => 'required|date_format:d/m/Y',

            // Kolom 'keterangan' dihapus dari validasi 'required'
            'keterangan' => 'nullable|string',

            'kode_verifikasi' => 'nullable|string',
            'waktu_masuk' => 'nullable',
            'waktu_pulang' => 'nullable',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // Fungsi ini harus ada agar SkipsOnFailure bekerja
    }
}
