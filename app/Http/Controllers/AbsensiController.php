<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\JamKerja;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AbsensiController extends Controller
{
    public function create(Request $request)
    {
        $query = Absensi::with('user');
        $jamKerjas = JamKerja::all();

        return view('absensi.create', compact('jamKerjas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_absen' => 'required|in:masuk,pulang',
            'jam_kerja_id' => 'required|exists:jam_kerjas,id',
            'foto' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda belum login.');
        }

        // 🔹 Ambil shift
        $jamKerja = JamKerja::find($request->jam_kerja_id);

        // 🔹 Simpan foto dari base64
        $imageData = $request->foto;
        $fileName = 'absen_' . time() . '.png';
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
            $image = substr($imageData, strpos($imageData, ',') + 1);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put('absensi/' . $fileName, base64_decode($image));
        }

        // 🔹 Simpan ke tabel absensis
        Absensi::create([
            'user_id' => $user->id,
            'jam_kerja_id' => $jamKerja->id,
            'tanggal_waktu' => now(), // otomatis waktu saat absen
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

        // ✅ Ambil parameter filter dari request, default bulan dan tahun sekarang
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $status = $request->input('status', '');
        $sort = $request->input('sort', 'desc');

        // ✅ Query dasar absensi
        $query = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal_waktu', $bulan)
            ->whereYear('tanggal_waktu', $tahun)
            ->with('jamKerja')
            ->orderBy('tanggal_waktu', $sort);

        // Ambil semua data dulu
        $data = $query->get();

        // ✅ Group berdasarkan tanggal (tanpa jam)
        $grouped = $data->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_waktu)->format('Y-m-d');
        });

        // ✅ Filter berdasarkan status (hadir / belum_pulang / tidak_hadir)
        if ($status) {
            $grouped = $grouped->filter(function ($records) use ($status) {
                $masuk = $records->firstWhere('tipe_absen', 'masuk');
                $pulang = $records->firstWhere('tipe_absen', 'pulang');

                return match ($status) {
                    'hadir' => $masuk && $pulang,
                    'belum_pulang' => $masuk && !$pulang,
                    'tidak_hadir' => !$masuk && !$pulang,
                    default => true,
                };
            });
        }

        // ✅ Pagination manual untuk Collection
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
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

    // Ambil bulan & tahun (default: bulan & tahun sekarang)
    $bulanInput = $request->get('bulan', date('n'));
    $tahun = $request->get('tahun', date('Y'));

    // Konversi nama bulan ke angka
    $bulanMap = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
    ];
    $bulan = $bulanMap[$bulanInput] ?? (int)$bulanInput;

    // Ambil semua data absensi user untuk bulan & tahun tersebut (tanpa filter status/search)
    $absensisData = Absensi::where('user_id', $user->id)
        ->whereMonth('tanggal_waktu', $bulan)
        ->whereYear('tanggal_waktu', $tahun)
        ->with('jamKerja')
        ->orderBy('tanggal_waktu', 'asc')
        ->get()
        ->groupBy(fn($item) => Carbon::parse($item->tanggal_waktu)->format('Y-m-d'));

    $bulanNama = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $fileName = 'Riwayat_Absensi_' . $user->name . '_' . $bulanNama[$bulan] . '_' . $tahun . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () use ($absensisData, $user) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        $delimiter = ';'; // agar Excel Indonesia baca dengan benar

        fputcsv($file, [
            'No', 'Departemen', 'Nama', 'No ID', 'Tanggal',
            'Waktu Masuk', 'Waktu Pulang', 'Total Jam', 'Shift', 'Keterangan'
        ], $delimiter);

        $no = 1;
        foreach ($absensisData as $tanggal => $records) {
            $masuk = $records->firstWhere('tipe_absen', 'masuk');
            $pulang = $records->firstWhere('tipe_absen', 'pulang');

            $totalJam = '-';
            if ($masuk && $pulang) {
                $totalJam = gmdate('H:i:s', strtotime($pulang->tanggal_waktu) - strtotime($masuk->tanggal_waktu));
            }

            $keterangan = 'Tidak Hadir';
            if ($masuk && $pulang) {
                $keterangan = 'Hadir';
            } elseif ($masuk && !$pulang) {
                $keterangan = 'Terlambat';
            }

            $shift = $masuk ? ($masuk->jamKerja->nama_shift ?? '-') : '-';

            fputcsv($file, [
                $no++,
                $user->departement ?? '-',
                $user->name,
                $user->badge_number ?? '-',
                Carbon::parse($tanggal)->format('d/m/Y'),
                $masuk ? Carbon::parse($masuk->tanggal_waktu)->format('H:i') : '-',
                $pulang ? Carbon::parse($pulang->tanggal_waktu)->format('H:i') : '-',
                $totalJam,
                $shift,
                $keterangan
            ], $delimiter);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}



    public function data(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda belum login.');
        }
        $query = Absensi::with('user', 'jamKerja');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_waktu', $request->tanggal);
        }

        // Filter berdasarkan user jika ada (misal untuk atasan atau HR melihat karyawan tertentu)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $absensis = $query->orderBy('tanggal_waktu', 'desc')->paginate(10);



        return view('absensi.data', compact('absensis', 'user'));
    }
}
