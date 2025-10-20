<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create()
    {
        // Kalau user sudah login, langsung redirect ke dashboard sesuai role
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectToDashboard($user);
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Coba login
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // Regenerate session (penting untuk keamanan)
        $request->session()->regenerate();

        $user = Auth::user();


        // Redirect sesuai role
        return $this->redirectToDashboard($user);
    }

    /**
     * Fungsi bantu untuk redirect sesuai role user
     */
    private function redirectToDashboard($user): RedirectResponse
    {
        switch ($user->role) {
            case 'hr':
                return redirect()->route('dashboard.hr');
            case 'direktur':
                return redirect()->route('dashboard.direktur');
            case 'atasan':
                return redirect()->route('dashboard.atasan');
            case 'karyawan':
                return redirect()->route('dashboard.karyawan');
            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Role tidak dikenali.']);
        }
    }

    /**
     * Logout user dan hapus session
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ langsung ke halaman login (tanpa loop)
        return redirect('/login');
    }
}
