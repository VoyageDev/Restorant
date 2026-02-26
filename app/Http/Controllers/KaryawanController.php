<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::with('user')->orderByDesc('created_at')->get();
        $users = User::orderBy('name')->get();

        if (request()->wantsJson()) {
            return response()->json([
                'karyawan' => $karyawan,
                'users' => $users,
            ]);
        }

        return view('components.karyawan', compact('karyawan', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'users_id' => ['required', 'integer', 'exists:users,id', 'unique:employees,users_id'],
            'jabatan' => ['required', 'in:Kasir,Waiter,Koki,Manajer'],
            'shift' => ['required', 'in:Pagi,Malam'],
            'no_hp' => ['required', 'string', 'max:50'],
            'tgl_masuk' => ['required', 'date'],
            'alamat' => ['required', 'string'],
        ]);

        $created = Karyawan::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Karyawan berhasil ditambahkan.',
                'data' => $created,
            ], 201);
        }

        return redirect()->route('karyawan');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'users_id' => ['required', 'integer', 'exists:users,id', 'unique:employees,users_id,'.$karyawan->id],
            'jabatan' => ['required', 'in:Kasir,Waiter,Koki,Manajer'],
            'shift' => ['required', 'in:Pagi,Malam'],
            'no_hp' => ['required', 'string', 'max:50'],
            'tgl_masuk' => ['required', 'date'],
            'alamat' => ['required', 'string'],
        ]);

        $karyawan->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Karyawan berhasil diperbarui.',
                'data' => $karyawan,
            ]);
        }

        return redirect()->route('karyawan');
    }

    public function show(Karyawan $karyawan)
    {
        return response()->json([
            'data' => $karyawan->load('user'),
        ]);
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return response()->json([
            'message' => 'Karyawan berhasil dihapus.',
        ]);
    }
}
