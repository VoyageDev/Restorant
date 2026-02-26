<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenUserController extends Controller
{
    public function index()
    {
        $users = User::with('karyawan')->orderByDesc('created_at')->get();

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $users,
            ]);
        }

        return view('components.manajemen_user', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'Role' => ['nullable', 'in:Manajer,Kasir'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'Role' => $validated['Role'],
            'status' => 'active',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User berhasil ditambahkan.',
                'data' => $user,
            ], 201);
        }

        return redirect()->route('manajemen-user');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'Role' => ['nullable', 'in:Manajer,Kasir'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'Role' => $validated['Role'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User berhasil diperbarui.',
                'data' => $user,
            ]);
        }

        return redirect()->route('manajemen-user');
    }

    public function show(User $user)
    {
        return response()->json([
            'data' => $user->load('karyawan'),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
