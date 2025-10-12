<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lembur;
use App\Models\JamKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LemburController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan lembur
     */
    public function create()
    {
        $jamKerjas = JamKerja::all(); // ambil daftar shift
        return view('lembur.create', compact('jamKerjas'));
    }

    /**
     * Menyimpan data lembur ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'section' => 'nullable|string|max:100',
            'jam_kerja_id' => 'required|exists:jam_kerjas,id',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
            'departemen' => 'required|string',
            'nama_karyawan' => 'required|string|max:100',
            'nama_atasan' => 'required|string|max:100',
            'job_description' => 'required|string',
            'tanda_tangan' => 'required|string', // base64 dari canvas
        ]);

        // Simpan paraf sebagai file gambar
        $imageData = $request->input('paraf');
        $imageName = 'paraf_' . time() . '.png';

        // Decode base64 dan simpan di storage/public/paraf/
        $imagePath = 'paraf/' . $imageName;
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        Storage::disk('public')->put($imagePath, base64_decode($image));

        // Simpan ke database
        Lembur::create([
            'user_id' => Auth::id(),
            'tgl_pengajuan' => $request->tanggal,
            'section' => $request->section,
            'jam_kerja_id' => $request->jam_kerja_id,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'departemen' => $request->departemen,
            'nama_karyawan' => $request->nama_karyawan,
            'nama_atasan' => $request->nama_atasan,
            'job_description' => $request->job_description,
            'paraf' => $imagePath,
            'status' => 'Diajukan',
            'tgl_pengajuan' => Carbon::now(),
        ]);

        return redirect()->route('lembur.data')->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    /**
     * Halaman untuk HR/atasan melihat semua pengajuan lembur
     */
    public function data(Request $request)
    {
        $query = Lembur::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
    public function approvalIndex()
    {
        $lemburs = Lembur::where('status', 'Diajukan')->orderBy('tgl_pengajuan', 'desc')->get();
        return view('lembur.approval', compact('lemburs'));
    }

    /**
     * Menyetujui lembur
     */
    public function approve(Request $request, Lembur $lembur)
    {
        $lembur->update([
            'status' => 'Disetujui',
            'disetujui_oleh' => Auth::user()->name,
            'tanggal_disetujui' => now(),
        ]);

        return back()->with('success', 'Lembur telah disetujui.');
    }

    /**
     * Menolak lembur
     */
    public function reject(Request $request, Lembur $lembur)
    {
        $lembur->update([
            'status' => 'Ditolak',
            'disetujui_oleh' => Auth::user()->name,
            'tanggal_disetujui' => now(),
        ]);

        return back()->with('error', 'Lembur telah ditolak.');
    }

    /**
     * Menampilkan riwayat lembur user login
     */
    public function riwayat()
    {
        $lemburs = Lembur::where('user_id', Auth::id())
            ->orderBy('tgl_pengajuan', 'desc')
            ->get();

        return view('lembur.riwayat', compact('lemburs'));
    }
}
