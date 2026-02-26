@extends('Admin')

@section('pageTitle', 'Manajemen User')
@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white border border-gray-100 rounded-xl shadow-sm">

        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Data User</h3>
            <a href="{{ route('manajemen-user') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Username / Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <p class="mt-1.5 text-xs text-gray-500">*Hanya isi jika ingin mengubah password akun ini.</p>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Role / Hak Akses</label>
                    <select name="Role"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="Owner" {{ old('Role', $user->Role) == 'Owner' ? 'selected' : '' }}>Owner</option>
                        <option value="Kasir" {{ old('Role', $user->Role) == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="Manajer" {{ old('Role', $user->Role) == 'Manajer' ? 'selected' : '' }}>Manajer
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status Akun</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg md:w-1/2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif (Bisa
                            Login)</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif
                            (Tidak Bisa Login)</option>
                    </select>
                    <p class="mt-1.5 text-xs text-gray-500">*Ubah menjadi Nonaktif jika karyawan sedang cuti panjang atau
                        sudah tidak bekerja.</p>
                </div>
            </div>

            <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Update Data User
                </button>
            </div>
        </form>
    </div>
@endsection
