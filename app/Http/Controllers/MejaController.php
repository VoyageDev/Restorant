<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mejas = Meja::latest()->get();
        $reservasis = Reservasi::with('meja')
            ->where('status', 'Pending')
            ->orWhere('status', 'Selesai')
            ->latest()
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'mejas' => $mejas,
                'reservasis' => $reservasis,
            ]);
        }

        return view('components.meja', compact('mejas', 'reservasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_meja' => 'required|string|unique:meja,no_meja|regex:/^[A-Z]-[0-9]{3}$/',
            'jumlah_orang' => 'required|integer|min:1',
        ], [
            'no_meja.regex' => 'Format nomor meja harus Huruf-Angka (Contoh: A-001)',
            'no_meja.unique' => 'Nomor meja sudah digunakan',
        ]);

        $validated['status'] = 'Tersedia';

        $meja = Meja::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Meja berhasil ditambahkan.',
                'data' => $meja,
            ], 201);
        }

        return redirect()->route('meja')
            ->with('success', 'Meja berhasil ditambahkan');
    }

    public function show(Meja $meja)
    {
        return response()->json([
            'data' => $meja->load('reservasis'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'no_meja' => 'required|string|regex:/^[A-Z]-[0-9]{3}$/|unique:meja,no_meja,'.$meja->id,
            'jumlah_orang' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Terisi,Reserved',
        ], [
            'no_meja.regex' => 'Format nomor meja harus Huruf-Angka (Contoh: A-001)',
        ]);

        $meja->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Meja berhasil diperbarui.',
                'data' => $meja,
            ]);
        }

        return redirect()->route('meja')
            ->with('success', 'Meja berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meja $meja)
    {
        $meja->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Meja berhasil dihapus.',
            ]);
        }

        return redirect()->route('meja')
            ->with('success', 'Meja berhasil dihapus');
    }

    // ========= CRUD Reservasi =========

    public function storeReservasi(Request $request)
    {
        $validated = $request->validate([
            'meja_id' => 'required|exists:meja,id',
            'nama_pelanggan' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'waktu_reservasi' => 'required|date',
        ]);

        $validated['status'] = 'Pending';

        $reservasi = Reservasi::create($validated);

        // Update status meja
        $meja = Meja::find($request->meja_id);
        $meja->update(['status' => 'Reserved']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservasi berhasil dibuat.',
                'data' => $reservasi,
            ], 201);
        }

        return redirect()->route('meja')
            ->with('success', 'Reservasi berhasil dibuat');
    }

    public function updateReservasi(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'waktu_reservasi' => 'required|date',
            'status' => 'required|in:Pending,Selesai,Dibatalkan',
        ]);

        $reservasi->update($validated);

        // Jika status dibatalkan atau selesai, ubah status meja jadi tersedia
        if (in_array($validated['status'], ['Dibatalkan', 'Selesai'])) {
            $reservasi->meja->update(['status' => 'Tersedia']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservasi berhasil diperbarui.',
                'data' => $reservasi,
            ]);
        }

        return redirect()->route('meja')
            ->with('success', 'Reservasi berhasil diperbarui');
    }

    public function destroyReservasi(Reservasi $reservasi)
    {
        // Ubah status meja jadi tersedia
        $reservasi->meja->update(['status' => 'Tersedia']);

        $reservasi->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Reservasi berhasil dibatalkan.',
            ]);
        }

        return redirect()->route('meja')
            ->with('success', 'Reservasi berhasil dibatalkan');
    }
}
