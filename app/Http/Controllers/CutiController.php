<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::where('user_id', Auth::id())->get();
        return view('cuti.create', compact('cutis'));
    }

    public function create()
    {
        return view('cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'jenis_cuti' => 'required|string',
            'tanda_tangan' => 'required|string',
            'nama_atasan' => 'required|string',
        ]);

        $user = Auth::user();
        $approverId = null;
        $status = 'menunggu';

        if ($user->role === 'karyawan') {
            $approverId = User::where('departemen_id', $user->departemen_id)
                            ->where('role', 'atasan')
                            ->first()?->id;
        } elseif ($user->role === 'atasan') {
            $approverId = User::where('role', 'hr')->first()?->id;
        } elseif ($user->role === 'hr') {
            $approverId = User::where('role', 'direktur')->first()?->id;
        } elseif ($user->role === 'direktur') {
            $status = 'disetujui';
        }

        Cuti::create([
            'user_id' => $user->id,
            'tgl_pengajuan' => now(),
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
            'jenis_cuti' => $request->jenis_cuti,
            'approver_id' => $approverId,
            'status_pengajuan' => $status,
            'nama_atasan' => $request->nama_atasan,
            'tanda_tangan' => $request->tanda_tangan,
        ]);

        return redirect()->route('cuti.create')->with('success', 'Pengajuan cuti berhasil diajukan!');
    }

    /**
     * Menampilkan halaman approval cuti (untuk atasan/HR/direktur)
     */
    public function approvalIndex(Request $request)
    {
        $user = Auth::user();
        
        // Filter
        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        // Query cuti yang perlu di-approve oleh user login
        $query = Cuti::where('approver_id', $user->id)
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun);

        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $cutis = $query->with('user')->paginate(10);

        return view('cuti.approval', compact('cutis', 'bulan', 'tahun'));
    }

    /**
     * Approve cuti (POST)
     */
    public function approve(Request $request, Cuti $cuti)
    {
        if (Auth::id() != $cuti->approver_id) {
            abort(403, 'Anda tidak berhak approve.');
        }

        $cuti->update([
            'status_pengajuan' => 'disetujui',
            'tgl_status' => now(),
            'komentar_admin' => $request->komentar_admin ?? null,
        ]);

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    /**
     * Reject cuti (POST)
     */
    public function reject(Request $request, Cuti $cuti)
    {
        if (Auth::id() != $cuti->approver_id) {
            abort(403, 'Anda tidak berhak menolak.');
        }

        $cuti->update([
            'status_pengajuan' => 'ditolak',
            'tgl_status' => now(),
            'komentar_admin' => $request->komentar_admin ?? null,
        ]);

        return back()->with('success', 'Cuti berhasil ditolak.');
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

        $query = Cuti::where('user_id', $user->id)
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
        $user = Auth::user();

        // Filter
        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $query = Cuti::with('user')
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun);

        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $cutis = $query->paginate(10);

        return view('cuti.data', compact('cutis', 'bulan', 'tahun'));
    }
}