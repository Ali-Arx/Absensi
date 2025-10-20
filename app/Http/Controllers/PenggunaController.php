<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use Maatwebsite\Excel\Validators\ValidationException; 
use Exception;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('join_date', $request->date);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('join_date', $request->tahun);
        }

        // Get paginated results
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pengguna.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'badge_number' => 'required|string|unique:users,badge_number',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'departement' => 'required|string',
            'jabatan' => 'required|string',
            'join_date' => 'required|date',
            'No_HP' => 'required|string',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8',
            'role' => 'required|in:hr,direktur,atasan,karyawan',
        ]);



        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $dataPengguna)
    {
        return view('pengguna.show', compact('dataPengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'badge_number' => [
                'required',
                'string',
                Rule::unique('users', 'badge_number')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'name' => 'required|string|max:255',
            'departement' => 'required|string',
            'jabatan' => 'required|string',
            'join_date' => 'required|date',
            'No_HP' => 'required|string',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:hr,direktur,atasan,karyawan',
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }



        $user->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil diupdate!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus!');
    }

    public function import(Request $request)
    {
        // 3. PERBAIKI VALIDASI: Tambahkan 'csv'
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            // Jalankan proses impor
            Excel::import(new UserImport, $request->file('file'));

            // Jika berhasil, kembalikan dengan pesan sukses
            return redirect()->route('pengguna.index')
                ->with('success', 'Data pengguna berhasil diimpor!');
        } catch (ValidationException $e) {
            // 4. TANGKAP ERROR VALIDASI
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                // Kumpulkan semua pesan error
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }

            // Kembalikan ke halaman sebelumnya dengan pesan error yang spesifik
            return redirect()->back()
                ->with('error', 'Gagal impor data. Periksa baris berikut: <br>' . implode('<br>', $errorMessages));
        } catch (Exception $e) {
            // 5. Tangani error umum lainnya
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
