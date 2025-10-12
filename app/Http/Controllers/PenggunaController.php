<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'employee_id' => 'required|string|unique:users,employee_id',
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'position' => 'required|string',
            'join_date' => 'required|date',
            'phone' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

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
    public function update(Request $request, User $dataPengguna)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:users,employee_id,' . $dataPengguna->id,
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'position' => 'required|string',
            'join_date' => 'required|date',
            'phone' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $dataPengguna->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $dataPengguna)
    {
        $dataPengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus!');
    }
}