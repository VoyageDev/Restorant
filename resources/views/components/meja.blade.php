@extends('Admin')

@section('pageTitle', 'Manajemen Meja')
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
        selectedMejaId: '',
        selectedMejaNo: '',
        editMode: false,
        editData: {},
        editReservasi: false,
        editReservasiData: {}
    }" class="grid grid-cols-1 gap-6 lg:grid-cols-2 items-start">

        {{-- Tambah Meja Biasa --}}
        <div
            class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-blue-500">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                x-text="editMode ? 'Edit Meja' : 'Tambah Meja Baru'"></h3>

            <form :action="editMode ? '/mejas/' + editData.id : '{{ route('mejas.store') }}'" method="POST"
                class="space-y-4" id="formMeja">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editMode ? 'PUT' : 'POST'">
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Meja</label>
                    <input type="text" name="no_meja" pattern="[A-Z]-[0-9]{3}"
                        title="Format harus Huruf Kapital diikuti strip dan 3 angka. Contoh: A-001"
                        placeholder="Cth: A-001, B-012" required x-bind:value="editMode ? editData.no_meja : ''"
                        class="w-full px-4 py-2 text-sm bg-gray-50 border dark:bg-gray-900 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase @error('no_meja') border-red-500 @enderror">
                    @error('no_meja')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">*Format wajib: Huruf-Angka (Contoh: A-001)</p>
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Kapasitas (Jumlah
                        Orang)</label>
                    <input type="number" name="jumlah_orang" min="1" placeholder="4" required
                        x-bind:value="editMode ? editData.jumlah_orang : ''"
                        class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900  border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('jumlah_orang') border-red-500 @enderror">
                    @error('jumlah_orang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="editMode">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" x-bind:value="editData.status"
                        class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Terisi">Terisi</option>
                        <option value="Reserved">Reserved</option>
                    </select>
                </div>

                <div class="pt-2 mt-4 border-t border-gray-100 dark:border-gray-700 flex gap-2">
                    <button type="button" x-show="editMode"
                        @click="editMode = false; editData = {}; document.getElementById('formMeja').reset()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-200  dark:text-white dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-text="editMode ? 'Update Meja' : 'Simpan Meja'">
                    </button>
                </div>
            </form>
        </div>

        {{-- Reservasi Meja --}}
        <div class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-purple-500"
            id="form-reservasi">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                x-text="editReservasi ? 'Edit Reservasi' : 'Buat Reservasi Meja'"></h3>

            <form :action="editReservasi ? '/reservasi/' + editReservasiData.id : '{{ route('reservasi.store') }}'"
                method="POST" class="space-y-4" id="formReservasi">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editReservasi ? 'PUT' : 'POST'">
                <input type="hidden" name="meja_id" x-model="editReservasi ? editReservasiData.meja_id : selectedMejaId">

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Meja yang
                        Dipilih</label>
                    <input type="text" x-bind:value="editReservasi ? editReservasiData.meja_no : selectedMejaNo"
                        placeholder="Pilih dari tabel di bawah &darr;" readonly required
                        class="w-full px-4 py-2 text-sm font-bold text-purple-700 bg-purple-50  dark:bg-gray-800  border border-purple-200 dark:border-purple-700 rounded-lg cursor-not-allowed focus:outline-none placeholder-purple-300 dark:placeholder-purple-400">
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                            Pelanggan</label>
                        <input type="text" name="nama_pelanggan" placeholder="Nama pemesan..." required
                            x-bind:value="editReservasi ? editReservasiData.nama_pelanggan : ''"
                            class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">No. Telp /
                            WhatsApp</label>
                        <input type="text" name="no_telepon" placeholder="08..." required
                            x-bind:value="editReservasi ? editReservasiData.no_telepon : ''"
                            class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu
                        Kedatangan</label>
                    <input type="datetime-local" name="waktu_reservasi" required
                        x-bind:value="editReservasi ? editReservasiData.waktu_reservasi : ''"
                        class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                </div>

                <div x-show="editReservasi">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" x-bind:value="editReservasiData.status"
                        class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                        <option value="Pending">Pending</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div class="pt-2 mt-4 border-t border-gray-100 dark:border-gray-700 flex gap-2">
                    <button type="button" x-show="editReservasi"
                        @click="editReservasi = false; editReservasiData = {}; document.getElementById('formReservasi').reset(); selectedMejaId = ''; selectedMejaNo = '';"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-200 dark:text-white dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-bold text-white transition-colors bg-purple-600 rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        x-text="editReservasi ? 'Update Reservasi' : 'Proses Reservasi'">
                    </button>
                </div>
            </form>
        </div>

        {{-- Table meja biasa --}}
        <div class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Daftar Meja</h3>

            <div class="relative w-full md:w-72 mb-3">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" placeholder="Cari Meja..."
                    class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">No. Meja</th>
                            <th class="px-4 py-3 font-medium text-center">Kapasitas</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                            <th class="px-4 py-3 font-medium text-center">Aksi (Pilih)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mejas as $meja)
                            <tr
                                class="bg-white border-b hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $meja->no_meja }}</td>
                                <td class="px-4 py-3 text-center">{{ $meja->jumlah_orang }} Orang</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $meja->status === 'Tersedia' ? 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200' : ($meja->status === 'Terisi' ? 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200' : ($meja->status === 'Reserved' ? 'text-purple-800 bg-purple-100 dark:bg-purple-900 dark:text-purple-200' : 'text-gray-800 bg-gray-100 dark:bg-gray-900 dark:text-gray-200')) }}">
                                        {{ $meja->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button
                                        @click="editMode = true; editData = { id: {{ $meja->id }}, no_meja: '{{ $meja->no_meja }}', jumlah_orang: {{ $meja->jumlah_orang }}, status: '{{ $meja->status }}' }; window.scrollTo({ top: 0, behavior: 'smooth' })"
                                        class="px-3 py-1.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded hover:bg-yellow-200 transition dark:bg-gray-700 dark:text-blue-500 dark:hover:bg-gray-600">
                                        Edit
                                    </button>
                                    @if ($meja->status === 'Tersedia')
                                        <button
                                            @click="selectedMejaId = '{{ $meja->id }}'; selectedMejaNo = '{{ $meja->no_meja }}'; document.getElementById('form-reservasi').scrollIntoView({ behavior: 'smooth' });"
                                            class="px-3 py-1.5 text-xs font-semibold text-purple-700 bg-purple-100 border border-purple-200 rounded hover:bg-purple-200 transition dark:bg-gray-700 dark:text-purple-400 dark:hover:bg-gray-600 dark:border-purple-700 ">
                                            Reservasi
                                        </button>
                                    @endif
                                    <form action="{{ route('mejas.destroy', $meja->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus meja ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200 dark:bg-gray-700 dark:text-red-500 dark:hover:bg-gray-600 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800 border-b">
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data meja. Tambahkan meja baru di samping.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Table Reservasi Meja --}}
        <div class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Daftar Reservasi Aktif</h3>

            <div class="relative w-full md:w-72 mb-3">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" placeholder="Cari Reservasi Meja..."
                    class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 text-center font-express">
                        <tr>
                            <th class="px-4 py-3 font-medium">Meja</th>
                            <th class="px-4 py-3 font-medium">Pelanggan</th>
                            <th class="px-4 py-3 font-medium">Waktu</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                            <th class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservasis as $reservasi)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-3 font-bold text-purple-700">{{ $reservasi->meja->no_meja }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-white">
                                    {{ $reservasi->nama_pelanggan }}<br>
                                    <span class="text-xs text-gray-400">{{ $reservasi->no_telepon }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $reservasi->waktu_reservasi->format('d M Y') }}<br>
                                    <span
                                        class="text-xs text-gray-400">{{ $reservasi->waktu_reservasi->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $reservasi->status === 'Pending' ? 'text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-200' : ($reservasi->status === 'Selesai' ? 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200' : 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ $reservasi->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button
                                        @click="editReservasi = true; editReservasiData = { id: {{ $reservasi->id }}, meja_id: {{ $reservasi->meja_id }}, meja_no: '{{ $reservasi->meja->no_meja }}', nama_pelanggan: '{{ $reservasi->nama_pelanggan }}', no_telepon: '{{ $reservasi->no_telepon }}', waktu_reservasi: '{{ $reservasi->waktu_reservasi->format('Y-m-d\\TH:i') }}', status: '{{ $reservasi->status }}' }; document.getElementById('form-reservasi').scrollIntoView({ behavior: 'smooth' });"
                                        class="px-2 py-1 text-xs font-medium text-blue-700  dark:text-blue-500 bg-blue-100 dark:bg-gray-700 rounded hover:bg-blue-200 dark:hover:bg-gray-600">Edit</button>
                                    <form action="{{ route('reservasi.destroy', $reservasi->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 dark:bg-gray-700 dark:text-red-500 rounded hover:bg-red-200 dark:hover:bg-gray-600">Batal</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 transition-colors">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    Belum ada reservasi aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
@endsection
