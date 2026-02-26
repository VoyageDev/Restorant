@extends('Admin')

@section('pageTitle', 'Manajemen Karyawan')

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

    <div class="space-y-8" x-data="{
        editMode: false,
        editData: {}
    }">

        <div
            class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-blue-500">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                x-text="editMode ? 'Edit Data Karyawan' : 'Tambah Data Karyawan Baru'"></h3>

            <form :action="editMode ? '{{ url('karyawan') }}/' + editData.id : '{{ route('karyawan.store') }}'"
                method="POST" id="formKaryawan">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editMode ? 'PUT' : 'POST'">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Cth: Agus Setiawan" required
                            x-bind:value="editMode ? editData.nama_lengkap : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tautkan ke Akun
                            Login</label>
                        <select name="users_id" required x-model="editMode ? editData.users_id : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="">-- Pilih Akun User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">*Pilih akun login sistem untuk karyawan
                            ini.</p>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan
                            Karyawan</label>
                        <select name="jabatan" required x-model="editMode ? editData.jabatan : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Waiter">Waiter</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Koki">Koki</option>
                            <option value="Manajer">Manajer Operasional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Shift Kerja</label>
                        <select name="shift" required x-model="editMode ? editData.shift : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="Pagi">Pagi</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">No. HP /
                            WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="08..." required
                            x-bind:value="editMode ? editData.no_hp : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai
                            Bekerja</label>
                        <input type="date" name="tgl_masuk" required x-bind:value="editMode ? editData.tgl_masuk : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                            Lengkap</label>
                        <textarea name="alamat" rows="2" placeholder="Masukkan alamat domisili..." required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors"
                            x-text="editMode ? editData.alamat : ''"></textarea>
                    </div>

                </div>

                <div class="flex pt-5 mt-6 border-t border-gray-100 dark:border-gray-700 gap-2">
                    <button type="button" x-show="editMode"
                        @click="editMode = false; editData = {}; document.getElementById('formKaryawan').reset()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-8 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        x-text="editMode ? 'Update Karyawan' : 'Simpan Data Karyawan'">
                    </button>
                </div>
            </form>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">

            <div class="flex flex-col items-center justify-between mb-6 space-y-3 md:flex-row md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Karyawan Aktif</h3>

                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama karyawan..."
                        class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Nama Karyawan</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Jabatan</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Shift</th>
                            <th scope="col" class="px-4 py-3 font-medium">No. HP</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal Masuk</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawan as $item)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Terkait:
                                        {{ $item->user->email ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-full">{{ $item->jabatan }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $item->shift }}</td>
                                <td class="px-4 py-3">{{ $item->no_hp }}</td>
                                <td class="px-4 py-3">{{ $item->tgl_masuk->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button type="button"
                                        @click="editMode = true; editData = { id: {{ $item->id }}, nama_lengkap: '{{ addslashes($item->nama_lengkap) }}', users_id: {{ $item->users_id }}, jabatan: '{{ $item->jabatan }}', shift: '{{ $item->shift }}', no_hp: '{{ $item->no_hp }}', tgl_masuk: '{{ $item->tgl_masuk->format('Y-m-d') }}', alamat: `{{ addslashes($item->alamat) }}` }; window.scrollTo({ top: 0, behavior: 'smooth' })"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded hover:bg-yellow-200 transition dark:bg-gray-700 dark:text-blue-500 dark:hover:bg-gray-600">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200 transition dark:bg-gray-700 dark:text-red-500 dark:hover:bg-gray-600">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="6">Belum
                                    ada
                                    data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Auto-hide alerts after 3 seconds
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
