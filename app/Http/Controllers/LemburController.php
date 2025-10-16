<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Lembur;
use App\Models\JamKerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LemburController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan lembur
     */
    public function create()
    {
        $jamKerjas = JamKerja::all(); // ambil daftar shift

        $user = Auth::user();
        $approvalUsers = collect(); // default kosong, tapi tetap collection

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
        if ($approvalUsers->isEmpty()) {
            $approvalUsers = collect(['Nama Atasan Tidak Tersedia']);
        }

        return view('lembur.create', compact('jamKerjas', 'approvalUsers'));
    }


    /**
     * Menyimpan data lembur ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_pengajuan' => 'required|date',
            'section' => 'nullable|string|max:100',
            'jam_kerja_id' => 'required|exists:jam_kerjas,id',
            'tgl_jam_mulai' => 'required',
            'tgl_jam_selesai' => 'required',
            'approver_id' => 'required',
            'deskripsi_kerja' => 'required|string',
            'tanda_tangan' => 'required|string',

        ]);

        $approverId = $request->approver_id;




        $mulai = Carbon::createFromFormat('H:i', $request->tgl_jam_mulai);
        $selesai = Carbon::createFromFormat('H:i', $request->tgl_jam_selesai);


        if ($selesai->lessThan($mulai)) {
            $selesai->addDay();
        }

        // Hitung total jam (dengan desimal, misal 8.5 jam)
        $totalJamKerja = $mulai->floatDiffInHours($selesai);


        // Simpan paraf sebagai file gambar
        $imageData = $request->input('tanda_tangan');
        $imageName = 'paraf_' . time() . '.png';

        // Decode base64 dan simpan di storage/public/paraf/
        $imagePath = 'paraf/' . $imageName;
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        Storage::disk('public')->put($imagePath, base64_decode($image));

        // Simpan ke database
        Lembur::create([
            'user_id' => Auth::id(),
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'section' => $request->section,
            'jam_kerja_id' => $request->jam_kerja_id,
            'tgl_jam_mulai' => $request->tgl_jam_mulai,
            'tgl_jam_selesai' => $request->tgl_jam_selesai,
            'approver_id' => $approverId,
            'total_jam_kerja' => $totalJamKerja,
            'tanda_tangan' => $imagePath,
            'deskripsi_kerja' => $request->deskripsi_kerja,
            'status_pengajuan' => 'menunggu',
        ]);

        return redirect()->route('lembur.create')->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    /**
     * Halaman untuk HR/atasan melihat semua pengajuan lembur
     */
    public function data(Request $request)
    {
        $query = Lembur::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status_pengajuan);
        }

        if ($request->filled('departemen')) {
            $query->where('departemen', $request->departemen);
        }

        $lemburs = $query->orderBy('tgl_pengajuan', 'desc')->get();

        return view('lembur.data', compact('lemburs'));
    }

    /**
     * Menampilkan halaman approval untuk atasan
     */
    public function approvalIndex(Request $request)
    {
        $user = Auth::user();

        $status_pengajuan = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $query = Lembur::with('user', 'approver')
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun)
            ->where('approver_id', $user->id); // ✅ hanya tampilkan pengajuan untuk approver yang sedang login

        // Optional filter status
        if ($status_pengajuan) {
            $query->where('status_pengajuan', $status_pengajuan);
        }

        $lemburs = $query->paginate(10);

        return view('lembur.approval', compact('lemburs', 'bulan', 'tahun'));
    }

    /**
     * Menyetujui lembur
     */
    public function approve(Request $request, Lembur $lembur)
    {
        $lembur->update([
            'status_pengajuan' => 'disetujui',
            'tgl_status' => now(),
        ]);

        return back()->with('success', 'Lembur telah disetujui.');
    }

    /**
     * Menolak lembur
     */
    public function reject(Request $request, Lembur $lembur)
    {
        $lembur->update([
            'status_pengajuan' => 'ditolak',
            'tgl_status' => now(),
        ]);

        return back()->with('error', 'Lembur telah ditolak.');
    }

    /**
     * Menampilkan riwayat lembur user login
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        // Filter
        $status = $request->get('status', '');
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        $query = lembur::with('approver')
            ->where('user_id', $user->id)
            ->whereMonth('tgl_pengajuan', $bulan)
            ->whereYear('tgl_pengajuan', $tahun);


        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $lemburs = $query->paginate(10);

        return view('lembur.riwayat', compact('lemburs', 'user', 'bulan', 'tahun'));
    }

    /**
     * Menampilkan detail lembur
     */
    public function show($id)
    {
        $lembur = Lembur::with(['user', 'approver'])->find($id);

        if (!$lembur) {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'data' => $lembur
        ]);
    }
}
