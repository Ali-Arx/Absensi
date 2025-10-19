<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::where('user_id', Auth::id())->get();
        return view('cuti.create', compact('cutis'));
    }

    public function create()
    {
        $user = Auth::user();
        $approvalUsers = [];

        if ($user->role === 'atasan') {

            $approvalUsers = User::where('role', 'hr')->get();
        } elseif ($user->role === 'karyawan') {
            if ($user->departement === 'Office') {

                $approvalUsers = User::whereIn('name', ['Yeni', 'Nadirman'])->get();
            } elseif ($user->departement === 'Sales') {
                $approvalUsers = User::whereIn('name', ['Nadirman', 'Defri'])->get();
            } elseif ($user->departement === 'Production') {
                $approvalUsers = User::whereIn('name', ['Zainuddin', 'Darwin'])->get();
            } elseif ($user->departement === 'Engineering') {
                $approvalUsers = User::whereIn('name', ['Rafly', 'Defri'])->get();
            }
        } elseif ($user->role === 'hr') {
            $approvalUsers = User::where('role', 'direktur')->get();
        }

        // Tambahan: Handle jika setelah semua logika, $approvalUsers masih kosong
        if (empty($approvalUsers)) {
            $approvalUsers = 'Nama Atasan Tidak Tersedia';
        }

        return view('cuti.create', compact('approvalUsers'));
    }

    public function store(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'tgl_mulai'     => 'required|date',
            'tgl_selesai'   => 'required|date|after_or_equal:tgl_mulai',
            'alasan'        => 'required|string',
            'jenis_cuti'    => 'required|string',
            'tanda_tangan'  => 'required|string',
            'approver_id'   => 'required|exists:users,id',
        ]);

        // ✅ Ambil user yang sedang login
        $user = Auth::user();
        $status = 'menunggu';
        $approverId = $request->approver_id;



        // ✅ Simpan pengajuan cuti
        Cuti::create([
            'user_id'          => $user->id,
            'tgl_pengajuan'    => now(),
            'tgl_mulai'        => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            'alasan'           => $request->alasan,
            'jenis_cuti'       => $request->jenis_cuti,
            'status_pengajuan' => $status,
            'approver_id'      => $approverId,
            'tanda_tangan'     => $request->tanda_tangan,
        ]);

        return redirect()
            ->route('cuti.create')
            ->with('success', 'Pengajuan cuti berhasil diajukan!');
    }


    /**
     * Menampilkan halaman approval cuti (untuk atasan/HR/direktur)
     */
    public function approvalIndex(Request $request)
    {
        $user = Auth::user();

        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $query = Cuti::with('user', 'approver')
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun)
            ->where('approver_id', $user->id);
        // Optional filter status
        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $cutis = $query->paginate(10);

        return view('cuti.approval', compact('cutis', 'bulan', 'tahun'));
    }

    /**
     * Menampilkan halaman riwayat cuti user login
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        // Filter
        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $query = Cuti::with('approver')
            ->where('user_id', $user->id)
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun);


        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $cutis = $query->paginate(10);

        return view('cuti.riwayat', compact('cutis', 'user', 'bulan', 'tahun'));
    }

    /**
     * Menampilkan data cuti (untuk HR/admin melihat semua data)
     */
    public function data(Request $request)
    {
        // Filter
        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $department = $request->get('department', '');

        // --- 2. AMBIL DAFTAR DEPARTEMEN UNIK ---
        // Mengambil semua 'department' yang unik dari tabel user,
        // mengabaikan yang null, dan mengurutkannya.
        $departments = User::select('departement')
            ->whereNotNull('departement')
            ->distinct()
            ->orderBy('departement', 'asc')
            ->pluck('departement');

        // Query Cuti (kode Anda sebelumnya sudah benar)
        $query = Cuti::with('user')
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun);

        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        if ($department) {
            $query->whereHas('user', function ($userQuery) use ($department) {
                $userQuery->where('departement', $department);
            });
        }

        $cutis = $query->paginate(10);

        // --- 3. KIRIMKAN $departments KE VIEW ---
        return view('cuti.data', compact(
            'cutis',
            'bulan',
            'tahun',
            'department',
            'departments' // <-- Tambahkan ini
        ));
    }

    public function show($id)
    {
        // Tambahkan relasi 'approver' agar bisa akses nama atasan
        $cuti = Cuti::with(['user', 'approver'])->findOrFail($id);

        return response()->json($cuti);
    }

    public function processApproval(Request $request, Cuti $cuti)
    {
        // 1. Validasi Input dari Form
        $validated = $request->validate([
            'status_pengajuan' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'komentar' => 'nullable|string|max:255',
            'tanda_tangan_approval' => 'required|string', // TTD wajib diisi
        ]);

        $imageData = $validated['ttd_atasan_base64'];


        $imageName = 'paraf_' . time() . '.png';

        // Decode base64 dan simpan di storage/public/paraf/
        $imagePath = 'tanda_tangan_atasan_cuti/' . $imageName;
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        Storage::disk('public')->put($imagePath, base64_decode($image));

        // 3. Update Data Cuti di Database
        $cuti->update([
            'status_pengajuan' => $validated['status_pengajuan'],
            'komentar' => $validated['komentar'],
            'tanda_tangan_approval' => $imagePath, // Simpan URL publik ke file
            'tgl_disetujui' => now(), // Catat tanggal persetujuan
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Status pengajuan cuti telah berhasil diperbarui.');
    }
}
