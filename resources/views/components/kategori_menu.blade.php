@extends('Admin')

@section('pageTitle', 'Kategori Menu')

@section('content')
    <div class="space-y-8">

        @if (session('success'))
            <div id="alert-success"
                class="fixed right-9 z-50 mb-6 p-4 bg-green-50 border border-green-200 rounded-lg w-auto max-w-md">
                <p class="text-sm font-medium text-green-800">
                    ✓ {{ session('success') }}
                </p>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error"
                class="fixed right-9 z-50 mb-6 p-4 bg-red-50 border border-red-200 rounded-lg w-auto max-w-md">
                <p class="text-sm font-medium text-red-800">
                    ✗ {{ session('error') }}
                </p>
            </div>
        @endif

        <div class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-smborder-gray-100 rounded-xl shadow-sm">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white">Tambah Kategori Menu Baru</h3>

            <form action="{{ route('kategori.store') }}" method="POST" id="formKategori">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
                        <input type="text" name="name" placeholder="Cth: Makanan Utama" required
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 text-sm dark:text-gray-300 dark:bg-gray-900 bg-gray-50 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-5 mt-6 border-t dark:border-gray-700 border-gray-100">
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Kategori Menu Baru
                    </button>
                </div>
            </form>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm">

            <div class="flex flex-col items-center justify-between mb-6 space-y-3 md:flex-row md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Kategori Menu Saat Ini</h3>

                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama kategori menu..."
                        class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Nama Kategori</th>
                            <th scope="col" class="px-4 py-3 font-medium">Jumlah Menu</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kategori)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700  transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $kategori->name }}</td>
                                <td class="px-4 py-3">{{ $kategori->menus_count }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editKategori({{ $kategori->id }}, '{{ $kategori->name }}')"
                                        class="inline-flex items-center px-3 py-1.5 mr-2 text-xs font-medium text-yellow-700 bg-yellow-100  dark:bg-gray-700 dark:text-blue-500 rounded hover:bg-yellow-200 dark:hover:bg-gray-600">
                                        Edit
                                    </button>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 dark:bg-gray-700 dark:text-red-500 rounded hover:bg-red-200 dark:hover:bg-gray-600">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data kategori. Tambahkan kategori baru di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kategoris->links() }}
            </div>
        </div>
    </div>

    <script>
        function editKategori(id, name) {
            const form = document.getElementById('formKategori');
            form.action = '/kategori/' + id;

            // Add hidden method field for PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            // Fill the input
            form.querySelector('input[name="name"]').value = name;

            // Change button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Kategori';

            // Add cancel button if not exists
            if (!form.querySelector('.btn-cancel')) {
                const cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.className =
                    'btn-cancel px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg shadow-sm hover:bg-gray-200 mr-3';
                cancelBtn.textContent = 'Batal';
                cancelBtn.onclick = resetForm;
                submitBtn.parentElement.insertBefore(cancelBtn, submitBtn);
            }

            // Scroll to form
            form.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function resetForm() {
            const form = document.getElementById('formKategori');
            form.action = '{{ route('kategori.store') }}';
            form.querySelector('input[name="name"]').value = '';

            // Remove method field
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();

            // Reset button
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Simpan Kategori Menu Baru';

            // Remove cancel button
            const cancelBtn = form.querySelector('.btn-cancel');
            if (cancelBtn) cancelBtn.remove();
        }

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
