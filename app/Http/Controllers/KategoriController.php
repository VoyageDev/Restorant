<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriMenu::withCount('menus')->paginate(10);

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $kategoris,
            ]);
        }

        return view('components.kategori_menu', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:kategori_menu,name|max:255',
        ]);

        $kategori = KategoriMenu::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil ditambahkan.',
                'data' => $kategori,
            ], 201);
        }

        return redirect()->route('kategori')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriMenu $kategori)
    {
        return response()->json($kategori);
    }

    public function show(KategoriMenu $kategori)
    {
        return response()->json([
            'data' => $kategori->loadCount('menus'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriMenu $kategori)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:kategori_menu,name,'.$kategori->id.'|max:255',
        ]);

        $kategori->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil diperbarui.',
                'data' => $kategori,
            ]);
        }

        return redirect()->route('kategori')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriMenu $kategori)
    {
        if ($kategori->menus()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki menu',
                ], 409);
            }

            return redirect()->route('kategori')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki menu');
        }

        $kategori->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil dihapus.',
            ]);
        }

        return redirect()->route('kategori')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
