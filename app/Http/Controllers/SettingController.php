<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first() ?? new Setting();

        $setting->update([
            'jam_masuk_default' => $request->jam_masuk_default,
            'jam_pulang_default' => $request->jam_pulang_default,
            'durasi_istirahat' => $request->durasi_istirahat,
            'maks_cuti_tahun' => $request->maks_cuti_tahun,
            'maks_cuti_bulan' => $request->maks_cuti_bulan,
            'cuti_minimal_sebelum_pengajuan' => $request->cuti_minimal_sebelum_pengajuan,
            'password_min_8' => $request->has('password_min_8'),
            'selfie_absensi' => $request->has('selfie_absensi'),
            'verifikasi_gps' => $request->has('verifikasi_gps'),
            'export_mingguan' => $request->has('export_mingguan'),
        ]);

        return response()->json(['success' => true]);
    }

    public function reset()
    {
        Setting::truncate();
        return response()->json(['success' => true]);
    }
}
