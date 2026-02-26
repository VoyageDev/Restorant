<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('kategori')->latest()->paginate(10);
        $kategoris = KategoriMenu::all();

        if (request()->wantsJson()) {
            return response()->json([
                'menus' => $menus,
                'kategoris' => $kategoris,
            ]);
        }

        return view('components.stock_menu', compact('menus', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriMenu::all();

        if (request()->wantsJson()) {
            return response()->json([
                'kategoris' => $kategoris,
            ]);
        }

        return view('crud.menu.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_menu_id' => 'required|exists:kategori_menu,id',
            'name' => 'required|string|unique:menu',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'daily_stock' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Habis',
        ]);

        $menu = Menu::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Menu berhasil ditambahkan.',
                'data' => $menu,
            ], 201);
        }

        return redirect()->route('stock-menu')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'data' => $menu->load('kategori'),
            ]);
        }

        return view('crud.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $kategoris = KategoriMenu::all();

        if (request()->wantsJson()) {
            return response()->json([
                'menu' => $menu,
                'kategoris' => $kategoris,
            ]);
        }

        return view('crud.menu.edit', compact('menu', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'kategori_menu_id' => 'required|exists:kategori_menu,id',
            'name' => 'required|string|unique:menu,name,'.$menu->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'daily_stock' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Habis',
        ]);

        $menu->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Menu berhasil diperbarui.',
                'data' => $menu,
            ]);
        }

        return redirect()->route('stock-menu')
            ->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Menu berhasil dihapus.',
            ]);
        }

        return redirect()->route('stock-menu')
            ->with('success', 'Menu berhasil dihapus');
    }
}
