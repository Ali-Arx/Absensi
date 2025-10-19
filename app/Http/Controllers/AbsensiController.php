<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\JamKerja;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\AbsensiExport;       // <-- 1. TAMBAHKAN INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AbsensiImport; // <-- Tambahkan ini di atas file Controller
use Maatwebsite\Excel\Validators\ValidationException; // <-- Tambahkan ini
use App\Exports\LaporanAbsensiExport;
use Exception;


class AbsensiController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $jamKerjas = JamKerja::all();

        // ✅ Cek status absensi hari ini
        $absenMasuk = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal_waktu', now()->toDateString())
            ->where('tipe_absen', 'masuk')
            ->first();

        $absenPulang = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal_waktu', now()->toDateString())
            ->where('tipe_absen', 'pulang')
            ->first();

        $sudahAbsenMasuk = $absenMasuk !== null;
        $sudahAbsenPulang = $absenPulang !== null;

        // ✅ Jika sudah absen masuk, ambil shift otomatis untuk absen pulang
        $selectedShift = $absenMasuk?->jam_kerja_id;

        return view('absensi.create', compact('jamKerjas', 'sudahAbsenMasuk', 'sudahAbsenPulang', 'selectedShift'));
    }



    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda belum login.');
        }

        $absenMasuk = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal_waktu', now()->toDateString())
            ->where('tipe_absen', 'masuk')
            ->first();

        $absenPulang = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal_waktu', now()->toDateString())
            ->where('tipe_absen', 'pulang')
            ->first();

        // ✅ Validasi awal
        $request->validate([
            'tipe_absen' => 'required|in:masuk,pulang',
            'foto' => 'required',
            'lokasi' => 'required|string|max:255',
            'jam_kerja_id' => $request->tipe_absen === 'masuk' ? 'required|exists:jam_kerjas,id' : 'nullable',
        ]);

        // ✅ Jika sudah absen masuk dan pulang — blok total
        if ($absenMasuk && $absenPulang) {
            return back()->with('error', 'Anda sudah menyelesaikan absensi hari ini.');
        }

        // ✅ Blokir absen masuk kedua kali
        if ($request->tipe_absen === 'masuk' && $absenMasuk) {
            return back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
        }

        // ✅ Blokir absen pulang tanpa absen masuk
        if ($request->tipe_absen === 'pulang' && !$absenMasuk) {
            return back()->with('error', 'Anda belum melakukan absensi masuk.');
        }

        // ✅ Blokir absen pulang kedua kali
        if ($request->tipe_absen === 'pulang' && $absenPulang) {
            return back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
        }

        // ✅ Tentukan shift
        $jamKerjaId = $request->tipe_absen === 'pulang'
            ? $absenMasuk->jam_kerja_id
            : $request->jam_kerja_id;

        // 🔹 Simpan foto dari base64
        $imageData = $request->foto;
        $fileName = 'absen_' . time() . '.png';
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
            $image = substr($imageData, strpos($imageData, ',') + 1);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put('absensi/' . $fileName, base64_decode($image));
        }

        // 🔹 Simpan ke tabel
        Absensi::create([
            'user_id' => $user->id,
            'jam_kerja_id' => $jamKerjaId,
            'tanggal_waktu' => now(),
            'tipe_absen' => $request->tipe_absen,
            'foto' => 'absensi/' . $fileName,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan!');
    }


    public function riwayat(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda belum login.');
        }

        // Filter bulan, tahun, status, urutan
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status', '');
        $sort = $request->input('sort', 'desc');

        // Ambil data absensi user
        $query = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal_waktu', $bulan)
            ->whereYear('tanggal_waktu', $tahun)
            ->with('jamKerja')
            ->orderBy('tanggal_waktu', $sort);


        $data = $query->get();

        // Tentukan keterangan dinamis (Hadir / Terlambat / Tidak Hadir)
        foreach ($data as $item) {
            // Kita HANYA peduli pada absensi 'masuk' untuk menentukan status hari itu
            if ($item->tipe_absen === 'masuk') {

                if ($item->jamKerja) {
                    $jamMasukNormalStr  = $item->jamKerja->jam_masuk ?? '08:00:00';
                    $jamKeluarNormalStr = $item->jamKerja->jam_keluar ?? '17:00:00';

                    $tanggalHariIni = Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
                    $jamMasukNormal   = Carbon::parse("$tanggalHariIni $jamMasukNormalStr");
                    $jamKeluarNormal  = Carbon::parse("$tanggalHariIni $jamKeluarNormalStr");
                    $jamAbsen         = Carbon::parse($item->tanggal_waktu);

                    // Tambahkan toleransi 10 menit
                    $jamMasukToleransi = $jamMasukNormal->copy()->addMinutes(10);

                    if ($jamAbsen->lt($jamMasukToleransi)) {
                        // Masuk sebelum atau tepat dalam 10 menit toleransi
                        $item->keterangan_dinamis = 'Hadir';
                    } elseif ($jamAbsen->between($jamMasukToleransi, $jamKeluarNormal)) {
                        $item->keterangan_dinamis = 'Hadir (Terlambat)';
                    } else {
                        // Datang setelah jam kerja berakhir
                        $item->keterangan_dinamis = 'Tidak Hadir';
                    }
                } else {
                    // Jika tidak ada jam kerja ter-set, anggap saja Hadir
                    $item->keterangan_dinamis = 'Hadir';
                }
            }
            // JANGAN TAMBAHKAN 'else' di sini. Biarkan absensi 'pulang' tidak memiliki
            // properti 'keterangan_dinamis'.
        }


        // Group berdasarkan tanggal
        $grouped = $data->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        });


        // Filter status (opsional)
        if ($status) {
            $grouped = $grouped->filter(function ($records) use ($status) {

                // Cari record 'masuk' untuk hari itu
                $masuk = $records->firstWhere('tipe_absen', 'masuk');

                // Tentukan status hari itu berdasarkan record 'masuk'
                $statusHariIni = 'Tidak Hadir'; // Default jika tidak ada record 'masuk'

                if ($masuk && isset($masuk->keterangan_dinamis)) {
                    // Jika ada record 'masuk' dan keterangannya sudah dihitung
                    $statusHariIni = $masuk->keterangan_dinamis;
                }

                // Bandingkan status hari itu dengan status dari filter
                return $statusHariIni === $status;
            });
        }

        // Pagination manual
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $items = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $absensis = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $grouped->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('absensi.riwayat', compact('absensis', 'user'));
    }



    public function export(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda belum login.');
        }

        // --- (SEMUA LOGIKA FILTER ANDA TETAP SAMA) ---
        // ... (salin semua logika query, filter, dan 'foreach' 
        //     untuk $item->keterangan_dinamis dari fungsi lama Anda) ...

        // Ini adalah kode dari fungsi 'export' Anda sebelumnya
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status', '');
        $sort = $request->input('sort', 'desc');

        $query = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal_waktu', $bulan)
            ->whereYear('tanggal_waktu', $tahun)
            ->with('jamKerja')
            ->orderBy('tanggal_waktu', $sort);

        $data = $query->get();

        foreach ($data as $item) {
            if ($item->tipe_absen === 'masuk') {
                if ($item->jamKerja) {
                    $jamMasukNormalStr  = $item->jamKerja->jam_masuk ?? '08:00:00';
                    $jamKeluarNormalStr = $item->jamKerja->jam_keluar ?? '17:00:00';
                    $tanggalHariIni = Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
                    $jamMasukNormal   = Carbon::parse("$tanggalHariIni $jamMasukNormalStr");
                    $jamKeluarNormal  = Carbon::parse("$tanggalHariIni $jamKeluarNormalStr");
                    $jamAbsen         = Carbon::parse($item->tanggal_waktu);
                    $jamMasukToleransi = $jamMasukNormal->copy()->addMinutes(10);

                    if ($jamAbsen->lt($jamMasukToleransi)) {
                        $item->keterangan_dinamis = 'Hadir';
                    } elseif ($jamAbsen->between($jamMasukToleransi, $jamKeluarNormal)) {
                        $item->keterangan_dinamis = 'Hadir (Terlambat)';
                    } else {
                        $item->keterangan_dinamis = 'Tidak Hadir';
                    }
                } else {
                    $item->keterangan_dinamis = 'Hadir';
                }
            }
        }

        $grouped = $data->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        });

        if ($status) {
            $grouped = $grouped->filter(function ($records) use ($status) {
                $masuk = $records->firstWhere('tipe_absen', 'masuk');
                $statusHariIni = 'Tidak Hadir';
                if ($masuk && isset($masuk->keterangan_dinamis)) {
                    $statusHariIni = $masuk->keterangan_dinamis;
                }
                return $statusHariIni === $status;
            });
        }
        // --- (SELESAI LOGIKA FILTER) ---


        // 3. GANTI BAGIAN EKSPOR LAMA ANDA

        // Tentukan nama file (ganti .csv menjadi .xlsx)
        $bulanNama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $fileName = 'Riwayat_Absensi_' . $user->name . '_' . $bulanNama[$bulan] . '_' . $tahun . '.xlsx'; // <-- GANTI .xlsx

        // Hapus $headers dan $callback lama

        // Cukup panggil Excel::download dengan Export Class baru Anda
        return Excel::download(new AbsensiExport($grouped, $user), $fileName);
    }

    public function exportAll(Request $request)
    {
        $user = Auth::user();

        // --- (MULAI) PERBAIKAN ---
        // Ambil input bulan (bisa jadi "Januari" atau "1")
        $bulanInput = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        // Konversi nama bulan ke angka
        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];

        // $bulan sekarang PASTI berupa angka (integer)
        $bulan = $bulanMap[$bulanInput] ?? (int)$bulanInput;

        $status_filter = $request->input('status', '');
        $user_id_filter = $request->input('user_id', '');
        // --- (SELESAI) PERBAIKAN ---

        // Query Anda (sudah benar, menggunakan $bulan yang sudah jadi angka)
        $query = Absensi::with(['user', 'jamKerja'])
            ->orderBy('tanggal_waktu', 'desc');

        $query->whereMonth('tanggal_waktu', $bulan);
        $query->whereYear('tanggal_waktu', $tahun);

        if ($user_id_filter) {
            $query->where('user_id', $user_id_filter);
        }

        $dataAbsensi = $query->get();

        $absensis = $dataAbsensi->groupBy(function ($item) {
            return $item->user_id . '_' . Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        });

        // ... (Semua logika 'foreach' dan 'if ($status_filter)' Anda tetap sama) ...
        foreach ($absensis as $key => $records) {
            $masuk = $records->firstWhere('tipe_absen', 'masuk');
            $pulang = $records->firstWhere('tipe_absen', 'pulang');

            $firstRecord = $records->first();
            $status_izin_sakit = $firstRecord->status ?? null;

            $status = 'Tidak Hadir';

            if ($status_izin_sakit === 'izin') {
                $status = 'Izin';
            } elseif ($status_izin_sakit === 'sakit') {
                $status = 'Sakit';
            } elseif ($masuk && $masuk->jamKerja) {
                $jamMasukNormalStr  = $masuk->jamKerja->jam_masuk ?? '08:00:00';
                $jamKeluarNormalStr = $masuk->jamKerja->jam_keluar ?? '16:00:00';
                $tanggalHariIni     = Carbon::parse($masuk->tanggal_waktu)->format('Y-m-d');
                $jamMasukNormal     = Carbon::parse("$tanggalHariIni $jamMasukNormalStr");
                $jamKeluarNormal    = Carbon::parse("$tanggalHariIni $jamKeluarNormalStr");
                $jamAbsen           = Carbon::parse($masuk->tanggal_waktu);

                if ($jamAbsen->lt($jamMasukNormal)) {
                    $status = 'Hadir';
                } elseif ($jamAbsen->between($jamMasukNormal, $jamKeluarNormal)) {
                    $status = 'Hadir (Terlambat)';
                } else {
                    $status = 'Tidak Hadir';
                }
            } elseif ($masuk) {
                $status = 'Hadir';
            }

            $records->status_hadir = $status;
        }

        if ($status_filter) {
            $absensis = $absensis->filter(function ($records) use ($status_filter) {
                $calculated_status = $records->status_hadir;

                return match ($status_filter) {
                    'hadir'     => $calculated_status === 'Hadir',
                    'terlambat' => $calculated_status === 'Hadir (Terlambat)',
                    'alpha'     => $calculated_status === 'Tidak Hadir',
                    'izin'      => $calculated_status === 'Izin',
                    'sakit'     => $calculated_status === 'Sakit',
                    default     => false,
                };
            });
        }
        // --- (Selesai kode salinan) ---

        // Tentukan nama file
        // $bulan (yang sekarang angka) digunakan di sini dan tidak akan error
        $namaBulan = Carbon::create()->month($bulan)->format('F');
        $fileName = 'Laporan_Absensi_All_' . $namaBulan . '_' . $tahun . '.xlsx';

        return Excel::download(new LaporanAbsensiExport($absensis), $fileName);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new AbsensiImport, $request->file('file'));

            return redirect()->back()->with('success', 'Laporan absensi berhasil diimpor!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . " (Nilai: " . $failure->values()[$failure->attribute()] . ")";
            }
            return redirect()->back()->with('error', 'Gagal impor data. Periksa baris berikut: <br>' . implode('<br>', $errorMessages));
        } catch (Exception $e) {
        // Tangani error umum lainnya
        Log::error('Gagal impor absensi: '." ". $e->getMessage());
        
        // TAMPILKAN PESAN ERROR YANG SEBENARNYA
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
    }



    public function data(Request $request)
    {
        $user = Auth::user();

        // --- PERUBAHAN 1: Ambil filter dari request, sesuaikan dengan form ---
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $status_filter = $request->input('status', ''); // Filter status dari form
        $user_id_filter = $request->input('user_id', ''); // Filter user (jika ada)

        // Ambil semua data absensi beserta relasi user & jam kerja
        $query = Absensi::with(['user', 'jamKerja'])
            ->orderBy('tanggal_waktu', 'desc');

        // --- PERUBAHAN 2: Terapkan filter Bulan dan Tahun ke Query DB ---
        // Hapus filter 'tanggal' yang lama, ganti dengan ini:
        $query->whereMonth('tanggal_waktu', $bulan);
        $query->whereYear('tanggal_waktu', $tahun);

        // Filter user jika diisi (kode Anda sebelumnya sudah benar)
        if ($user_id_filter) {
            $query->where('user_id', $user_id_filter);
        }

        // Ambil semua data
        $dataAbsensi = $query->get();

        // Grouping per user + tanggal (kode Anda sebelumnya sudah benar)
        $absensis = $dataAbsensi->groupBy(function ($item) {
            return $item->user_id . '_' . Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        });

        // --- PERUBAHAN 3: Logika status diperbarui untuk menangani Izin & Sakit ---
        foreach ($absensis as $key => $records) {
            $masuk = $records->firstWhere('tipe_absen', 'masuk');
            $pulang = $records->firstWhere('tipe_absen', 'pulang');

            // Cek status Izin/Sakit. Asumsi Anda menyimpannya di kolom 'status'
            // Cek data pertama dalam grup untuk status ini
            $firstRecord = $records->first();
            $status_izin_sakit = $firstRecord->status ?? null; // Ganti 'status' jika nama kolomnya beda

            $status = 'Tidak Hadir'; // Default, dianggap 'Alpha'

            if ($status_izin_sakit === 'izin') {
                $status = 'Izin';
            } elseif ($status_izin_sakit === 'sakit') {
                $status = 'Sakit';
            } elseif ($masuk && $masuk->jamKerja) {
                // Logika "Hadir" dan "Terlambat" Anda
                $jamMasukNormalStr  = $masuk->jamKerja->jam_masuk ?? '08:00:00';
                $jamKeluarNormalStr = $masuk->jamKerja->jam_keluar ?? '16:00:00';
                $tanggalHariIni     = Carbon::parse($masuk->tanggal_waktu)->format('Y-m-d');
                $jamMasukNormal     = Carbon::parse("$tanggalHariIni $jamMasukNormalStr");
                $jamKeluarNormal    = Carbon::parse("$tanggalHariIni $jamKeluarNormalStr");
                $jamAbsen           = Carbon::parse($masuk->tanggal_waktu);

                if ($jamAbsen->lt($jamMasukNormal)) {
                    $status = 'Hadir'; // datang sebelum jam masuk
                } elseif ($jamAbsen->between($jamMasukNormal, $jamKeluarNormal)) {
                    $status = 'Hadir (Terlambat)'; // datang setelah jam masuk
                } else {
                    $status = 'Tidak Hadir'; // datang setelah shift selesai
                }
            } elseif ($masuk) {
                // Jika ada data 'masuk' tapi tidak ada 'jamKerja'
                $status = 'Hadir'; // Anggap 'Hadir' (atau '-' seperti kode Anda)
            }

            // Simpan status yang sudah dihitung ke koleksi
            $records->status_hadir = $status;
        }

        // --- PERUBAHAN 4: Terapkan filter status SETELAH status dihitung ---
        if ($status_filter) {
            $absensis = $absensis->filter(function ($records) use ($status_filter) {
                $calculated_status = $records->status_hadir; // Status yg baru kita hitung

                // Cocokkan nilai form ('terlambat') dengan nilai yg dihitung ('Hadir (Terlambat)')
                return match ($status_filter) {
                    'hadir'     => $calculated_status === 'Hadir',
                    'terlambat' => $calculated_status === 'Hadir (Terlambat)',
                    'alpha'     => $calculated_status === 'Tidak Hadir',
                    default     => false,
                };
            });
        }

        // Anda mungkin ingin menambahkan Paginasi manual di sini seperti di controller 'riwayat'
        // ... (kode paginasi) ...

        return view('absensi.data', compact('absensis', 'user'));
    }
}
