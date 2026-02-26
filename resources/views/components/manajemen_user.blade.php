@extends('Admin')

@section('pageTitle', 'Manajemen User')

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

        {{-- Form Tambah User --}}
        <div
            class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-blue-500">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                x-text="editMode ? 'Edit Data User' : 'Tambah User Baru'"></h3>

            <form :action="editMode ? '{{ url('users') }}/' + editData.id : '{{ route('users.store') }}'" method="POST"
                id="formUser">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editMode ? 'PUT' : 'POST'">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">

                    <div class="lg:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Cth: Budi Santoso" required
                            x-bind:value="editMode ? editData.name : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div class="lg:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email</label>
                        <input type="email" name="email" placeholder="kasir@restoran.com" required
                            x-bind:value="editMode ? editData.email : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div class="lg:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password <span
                                x-show="editMode" class="text-xs text-gray-500">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password"
                            :placeholder="editMode ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter'"
                            :required="!editMode"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    </div>

                    <div class="lg:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role / Hak
                            Akses</label>
                        <select name="Role" x-model="editMode ? editData.Role : ''"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="">Pilih Role...</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Manajer">Manajer</option>
                        </select>
                    </div>

                    <div class="lg:col-span-1" x-show="editMode">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" x-model="editData.status"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex pt-5 mt-6 border-t border-gray-100 dark:border-gray-700 gap-2">
                    <button type="button" x-show="editMode"
                        @click="editMode = false; editData = {}; document.getElementById('formUser').reset()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-8 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        x-text="editMode ? 'Update User' : 'Simpan User'">
                    </button>
                </div>
            </form>
        </div>

        {{-- Table User --}}
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">

            <div class="flex flex-col items-center justify-between mb-6 space-y-3 md:flex-row md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar User Sistem</h3>

                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama atau email..."
                        class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                </div>
            </div>

            @php
                $roleBadge = [
                    'Owner' => 'text-amber-800 bg-amber-100 dark:bg-amber-900 dark:text-amber-200',
                    'Manajer' => 'text-purple-800 bg-purple-100 dark:bg-purple-900 dark:text-purple-200',
                    'Kasir' => 'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-200',
                ];
                $statusBadge = [
                    'active' => 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200',
                    'inactive' => 'text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300',
                ];
            @endphp

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Nama User</th>
                            <th scope="col" class="px-4 py-3 font-medium">Email</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Role</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Status</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Terakhir Aktif</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-full {{ $roleBadge[$user->Role] ?? 'text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300' }}">{{ $user->Role }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusBadge[$user->status] ?? 'text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300' }}">{{ ucfirst($user->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-full">{{ $user->last_active_at?->format('Y-m-d H:i') ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center space-x-1">
                                    <button type="button"
                                        @click="editMode = true; editData = { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', Role: '{{ $user->Role }}', status: '{{ $user->status }}' }; window.scrollTo({ top: 0, behavior: 'smooth' })"
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
                                <td class="px-4 py-6 text-center" colspan="6">Belum ada user.</td>
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
