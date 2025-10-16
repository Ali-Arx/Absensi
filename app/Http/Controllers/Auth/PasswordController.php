<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            // Lakukan validasi seperti biasa
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);

            // Jika validasi berhasil, update password
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Kirim pesan sukses
            return back()->with('password_success', 'password-updated');
        } catch (ValidationException $e) {
            // Tangkap exception jika validasi GAGAL

            // Cek apakah error spesifiknya adalah 'current_password'
            if ($e->validator->errors()->has('current_password')) {
                // Jika ya, kembalikan dengan session 'password_error' kustom
                return back()->with('password_error', 'Password lama yang Anda masukkan salah.');
            }

            // Jika error validasi lainnya, biarkan Laravel menanganinya seperti biasa
            throw $e;
        }
    }
}
