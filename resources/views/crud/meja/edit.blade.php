@extends('Admin')

@section('pageTitle', 'Manajemen Meja')
@section('content')
    <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm border-t-4 border-t-purple-500" id="form-reservasi">
        <h3 class="mb-5 text-lg font-semibold text-gray-800">Buat Reservasi Meja</h3>

        <form action="/reservasi/tambah" method="POST" class="space-y-4">
            <input type="hidden" name="meja_id" x-model="selectedMejaId">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Meja yang Dipilih</label>
                <input type="text" x-model="selectedMejaNo" placeholder="Pilih dari tabel di bawah &darr;" readonly
                    required
                    class="w-full px-4 py-2 text-sm font-bold text-purple-700 bg-purple-50 border border-purple-200 rounded-lg cursor-not-allowed focus:outline-none placeholder-purple-300">
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" placeholder="Nama pemesan..." required
                        class="w-full px-4 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">No. Telp / WhatsApp</label>
                    <input type="text" name="no_telepon" placeholder="08..." required
                        class="w-full px-4 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                </div>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Waktu Kedatangan</label>
                <input type="datetime-local" name="waktu_reservasi" required
                    class="w-full px-4 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
            </div>

            <div class="pt-2 mt-4 border-t border-gray-100">
                <button type="submit"
                    class="w-full px-4 py-2.5 text-sm font-bold text-white transition-colors bg-purple-600 rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    Proses Reservasi
                </button>
            </div>
        </form>
    </div>
@endsection
