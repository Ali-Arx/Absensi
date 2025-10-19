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

            
            return back()->with('password_success', 'password-updated');
        } catch (ValidationException $e) {
            
            if ($e->validator->errors()->has('current_password')) {
                return back()->with('password_error', 'Password lama yang Anda masukkan salah.');
            }
            throw $e;
        }
    }
}
