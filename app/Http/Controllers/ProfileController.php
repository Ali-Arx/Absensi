<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Ambil data yang sudah divalidasi
        $validatedData = $request->validated();

        // 2. Handle upload file foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($request->user()->foto) {
                Storage::delete('public/' . $request->user()->foto);
            }

            // Simpan foto baru dan dapatkan path-nya
            $path = $request->file('foto')->store('profile-photos', 'public');
            $validatedData['foto'] = $path;
        }

        // 3. Isi dan simpan data user
        $request->user()->fill($validatedData);

        // Reset verifikasi email jika email diubah
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // 4. Redirect dengan pesan sukses
        return Redirect::route('profile.edit')->with('success', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
