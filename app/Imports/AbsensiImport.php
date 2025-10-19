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
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) 
            {
                // 1. Temukan User berdasarkan 'No ID' (badge_number)
                // Kita cache user untuk performa
                static $userCache = [];
                $badge_number = $row['no_id'];
                if (!isset($userCache[$badge_number])) {
                    $userCache[$badge_number] = User::where('badge_number', $badge_number)->first();
                }
                $user = $userCache[$badge_number];

                if (!$user) {
                    continue; // Lewati baris ini jika user tidak ditemukan
                }

                // 2. Temukan Shift (JamKerja) berdasarkan nama
                static $jamKerjaCache = [];
                $shiftNama = $row['shift'] ?? '-';
                $jamKerjaId = null;
                if ($shiftNama !== '-') {
                    if (!isset($jamKerjaCache[$shiftNama])) {
                        $jamKerjaCache[$shiftNama] = JamKerja::where('nama_shift', $shiftNama)->first();
                    }
                    $jamKerja = $jamKerjaCache[$shiftNama];
                    $jamKerjaId = $jamKerja ? $jamKerja->id : null;
                }

                // 3. Parse Tanggal (dari format d/m/Y)
                try {
                    $tanggal = Carbon::createFromFormat('d/m/Y', $row['tanggal']);
                } catch (\Exception $e) {
                    continue; // Lewati jika format tanggal salah
                }
                $tanggalString = $tanggal->format('Y-m-d');

                // 4. Ambil Keterangan
                $keterangan = $row['keterangan'] ?? 'Tidak Hadir';
                $waktuMasuk = $row['waktu_masuk'] ?? '-';
                $waktuPulang = $row['waktu_pulang'] ?? '-';

                // 5. Logika Impor berdasarkan Keterangan
                if ($keterangan === 'Izin' || $keterangan === 'Sakit') {
                    // Hapus data 'pulang' jika ada
                    Absensi::where('user_id', $user->id)
                           ->where(DB::raw("DATE(tanggal_waktu)"), $tanggalString)
                           ->where('tipe_absen', 'pulang')
                           ->delete();
                    
                    // Buat/Update data 'masuk' dengan status Izin/Sakit
                    Absensi::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'tipe_absen' => 'masuk',
                            DB::raw("DATE(tanggal_waktu)") => $tanggalString
                        ],
                        [
                            'tanggal_waktu' => $tanggal->copy()->startOfDay(), // Set ke jam 00:00
                            'jam_kerja_id' => $jamKerjaId,
                            'status' => strtolower($keterangan) // 'izin' atau 'sakit'
                        ]
                    );

                } elseif ($keterangan === 'Tidak Hadir') {
                    // Keterangan 'Tidak Hadir' (Alpha) berarti hapus semua data hari itu
                    Absensi::where('user_id', $user->id)
                           ->where(DB::raw("DATE(tanggal_waktu)"), $tanggalString)
                           ->delete();

                } else { 
                    // Status 'Hadir' atau 'Hadir (Terlambat)'
                    
                    // Proses Waktu Masuk
                    if (!empty($waktuMasuk) && $waktuMasuk !== '-') {
                        $tanggalWaktuMasuk = $tanggal->copy()->setTimeFromTimeString($waktuMasuk);
                        Absensi::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'tipe_absen' => 'masuk',
                                DB::raw("DATE(tanggal_waktu)") => $tanggalString
                            ],
                            [
                                'tanggal_waktu' => $tanggalWaktuMasuk,
                                'jam_kerja_id' => $jamKerjaId,
                                'status' => null // Hapus status 'izin'/'sakit'
                            ]
                        );
                    }

                    // Proses Waktu Pulang
                    if (!empty($waktuPulang) && $waktuPulang !== '-') {
                        $tanggalWaktuPulang = $tanggal->copy()->setTimeFromTimeString($waktuPulang);
                        Absensi::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'tipe_absen' => 'pulang',
                                DB::raw("DATE(tanggal_waktu)") => $tanggalString
                            ],
                            [
                                'tanggal_waktu' => $tanggalWaktuPulang,
                                'jam_kerja_id' => $jamKerjaId,
                                'status' => null
                            ]
                        );
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Anda bisa melempar error di sini agar ditangkap controller
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'no_id' => 'required|string',
            'tanggal' => 'required|date_format:d/m/Y',
            'keterangan' => 'required|string',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // Fungsi ini harus ada agar SkipsOnFailure bekerja
    }
}