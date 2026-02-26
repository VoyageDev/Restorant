@extends('Admin')

@section('pageTitle', 'Manajemen Menu')
@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white border border-gray-100 rounded-xl shadow-sm">

        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Menu Baru</h3>
            <a href="/menu" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form class="space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Menu</label>
                    <input type="text" placeholder="Masukkan nama menu..." value="{{ old('nama', $menu->nama) }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div x-data="{ open: false, search: '', selected: '' }" class="relative z-20">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                    <div @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100">
                        <span x-text="selected === '' ? 'Pilih Kategori...' : selected"
                            class="text-sm text-gray-600"></span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg"
                        style="display: none;">
                        <div class="p-2 border-b">
                            <input type="text" x-model="search" placeholder="Cari kategori..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <ul class="overflow-y-auto max-h-40">
                            <li @click="selected = 'Makanan Utama'; open = false"
                                class="px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-blue-50 hover:text-blue-600">
                                Makanan Utama</li>
                            <li @click="selected = 'Minuman'; open = false"
                                class="px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-blue-50 hover:text-blue-600">
                                Minuman</li>
                            <li @click="selected = 'Snack / Cemilan'; open = false"
                                class="px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-blue-50 hover:text-blue-600">
                                Snack / Cemilan</li>
                        </ul>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-semibold text-gray-500">Rp</span>
                        <input type="text" placeholder="0"
                            class="w-full py-2.5 pl-12 pr-4 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Stock Utama</label>
                    <input type="number" min="0" placeholder="0"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Daily Stock (Stok Harian)</label>
                    <input type="number" min="0" placeholder="0"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

            </div>

            <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
@endsection
