@extends('Admin')

@section('pageTitle', 'Stock Menu')

@section('content')
    @if (session('success'))
        <div id="alert-success"
            class="fixed right-9 z-50 mb-6 p-4 bg-green-50 border border-green-200 rounded-lg w-auto max-w-md">
            <p class="text-sm font-medium text-green-800">
                ✓ {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="fixed right-9 z-50 mb-6 p-4 bg-red-50 border border-red-200 rounded-lg w-auto max-w-md">
            <p class="text-sm font-medium text-red-800">
                ✗ {{ session('error') }}
            </p>
        </div>
    @endif

    <div x-data="{
        editMode: false,
        editData: {},
    }" class="space-y-8">

        {{-- Form Tambah/Edit Menu --}}
        <div
            class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-blue-500">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                x-text="editMode ? 'Edit Menu' : 'Tambah Menu Baru'"></h3>

            <form :action="editMode ? '/menu/' + editData.id : '{{ route('menu.store') }}'" method="POST" id="formMenu">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editMode ? 'PUT' : 'POST'">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Menu</label>
                        <input type="text" name="name" placeholder="Cth: Nasi Goreng" required
                            x-bind:value="editMode ? editData.name : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                        <select name="kategori_menu_id" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="">Pilih Kategori...</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    x-bind:selected="editMode && editData.kategori_menu_id == {{ $kategori->id }}">
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Harga (Rp)</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 font-semibold text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" name="price" placeholder="0" min="0" required
                                x-bind:value="editMode ? editData.price : ''"
                                class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Stock Utama</label>
                        <input type="number" name="stock" min="0" placeholder="0" required
                            x-bind:value="editMode ? editData.stock : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Daily Stock</label>
                        <input type="number" name="daily_stock" min="0" placeholder="0" required
                            x-bind:value="editMode ? editData.daily_stock : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="Tersedia" x-bind:selected="editMode && editData.status === 'Tersedia'">Tersedia
                            </option>
                            <option value="Habis" x-bind:selected="editMode && editData.status === 'Habis'">Habis
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-5 mt-6 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" x-show="editMode"
                        @click="editMode = false; editData = {}; document.getElementById('formMenu').reset()"
                        class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        x-text="editMode ? 'Update Menu' : 'Simpan Menu Baru'">
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Daftar Menu --}}
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">

            <div class="flex flex-col items-center justify-between mb-6 space-y-3 md:flex-row md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Menu Saat Ini</h3>

                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama menu..."
                        class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Nama Menu</th>
                            <th scope="col" class="px-4 py-3 font-medium">Kategori</th>
                            <th scope="col" class="px-4 py-3 font-medium text-right">Harga</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Stock</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Daily Stock</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Stock Hari Ini</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Status</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $menu->name }}</td>

                                <td class="px-4 py-3">{{ $menu->kategori->name ?? '-' }}</td>

                                <td class="px-4 py-3 text-right">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>

                                <td class="px-4 py-3 text-center">{{ $menu->stock }}</td>

                                <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400 font-bold">
                                    {{ $menu->daily_stock }}</td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-bold {{ $menu->daily_stock_remaining > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $menu->daily_stock_remaining }} / {{ $menu->daily_stock }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ $menu->daily_stock > 0 ? round(($menu->daily_stock_remaining / $menu->daily_stock) * 100) : 0 }}%)
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-full {{ $menu->status === 'Tersedia' ? 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200' : 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $menu->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center space-x-1">
                                    <button type="button"
                                        @click="editMode = true; editData = { id: {{ $menu->id }}, name: '{{ $menu->name }}', kategori_menu_id: {{ $menu->kategori_menu_id }}, price: {{ $menu->price }}, stock: {{ $menu->stock }}, daily_stock: {{ $menu->daily_stock }}, status: '{{ $menu->status }}' }; window.scrollTo({ top: 0, behavior: 'smooth' })"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded hover:bg-yellow-200 transition dark:bg-gray-700 dark:text-blue-500 dark:hover:bg-gray-600">
                                        Edit
                                    </button>

                                    <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200 transition dark:bg-gray-700 dark:text-red-500 dark:hover:bg-gray-600">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada menu yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $menus->links() }}
            </div>
        </div>

        <script>
            setTimeout(() => {
                const alerts = document.querySelectorAll('#alert-success, #alert-error');
                alerts.forEach(alert => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);
        </script>
    </div>
@endsection
