@extends('Admin')

@section('pageTitle', 'Pembayaran')

@section('content')

    @if (session('success'))
        <div id="alert-success"
            class="fixed right-9 top-24 z-50 mb-6 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg w-auto max-w-md shadow-lg">
            <p class="text-sm font-medium text-green-800 dark:text-green-100">
                ✓ {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error"
            class="fixed right-9 top-24 z-50 mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg w-auto max-w-md shadow-lg">
            <p class="text-sm font-medium text-red-800 dark:text-red-100">
                ✗ {{ session('error') }}
            </p>
        </div>
    @endif
    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

        <div class="space-y-6 lg:col-span-2">

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-blue-500">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Meja untuk
                    Pembayaran</label>

                <select onchange="window.location.href='{{ route('pembayaran') }}?trx_id=' + this.value"
                    class="w-full px-4 py-2.5 text-sm font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-gray-900 border border-blue-200 dark:border-gray-600 rounded-lg md:w-1/2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">

                    <option value="">-- Cari / Pilih Meja Aktif --</option>

                    @forelse ($transaksiAktif as $trx)
                        <option value="{{ $trx->id }}" {{ request('trx_id') == $trx->id ? 'selected' : '' }}>
                            Meja {{ $trx->meja->no_meja }} ({{ $trx->waiter_name }}) - {{ $trx->no_trx }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada pesanan yang perlu dibayar saat ini</option>
                    @endforelse

                </select>
            </div>

            <div
                class="p-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm min-h-[400px]">

                @if ($selectedTransaksi)
                    <div class="font-mono text-sm text-gray-700 dark:text-gray-300">

                        <div class="pb-4 mb-4 border-b-2 border-gray-300 dark:border-gray-600 border-dashed text-center">
                            <h2 class="text-xl font-bold tracking-widest text-gray-900 dark:text-white">RESTORAN UTAMA</h2>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jl. Contoh Alamat No. 123, Kota</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Telp: 0812-3456-7890</p>
                        </div>

                        <div
                            class="flex justify-between pb-4 mb-4 border-b-2 border-gray-300 dark:border-gray-600 border-dashed">
                            <div>
                                <p>No. Trx : <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $selectedTransaksi->no_trx }}</span>
                                </p>
                                <p>Meja : <span class="font-semibold text-gray-900 dark:text-white">Meja
                                        {{ $selectedTransaksi->meja->no_meja }}</span></p>
                            </div>
                            <div class="text-right">
                                <p>Tanggal : {{ $selectedTransaksi->created_at->format('d M Y') }}</p>
                                <p>Kasir : {{ Auth::user()->name ?? 'Admin' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach ($selectedTransaksi->detailTransactions as $detail)
                                <div class="flex justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $detail->menu->name ?? 'Menu Dihapus' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $detail->jumlah_pesanan }} x
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-medium text-gray-900 dark:text-white">Rp
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4 mt-4 border-t-2 border-gray-300 dark:border-gray-600 border-dashed">
                            <div class="flex justify-between">
                                <p>Subtotal</p>
                                <p>Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between mt-1">
                                <p>Pajak (10%)</p>
                                <p>Rp {{ number_format($pajak, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between mt-3 text-base font-bold text-gray-900 dark:text-white">
                                <p>GRAND TOTAL</p>
                                <p>Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full py-16 text-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Struk Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silakan pilih meja yang akan dibayar pada
                            pilihan di atas.</p>
                    </div>
                @endif

            </div>

        </div>

        <div class="lg:col-span-1 lg:sticky lg:top-24">

            <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                <h3 class="mb-5 text-lg font-bold text-gray-800 dark:text-white">Detail Pembayaran</h3>

                <form action="{{ $selectedTransaksi ? route('pembayaran.update', $selectedTransaksi->id) : '#' }}"
                    method="POST" class="space-y-5">
                    @csrf

                    @if ($selectedTransaksi)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Total Tagihan</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 font-semibold text-gray-500 dark:text-gray-400">Rp</span>

                            <input type="text" id="total_tagihan"
                                value="{{ $selectedTransaksi ? number_format($grandTotal, 0, '', '.') : '' }}"
                                placeholder="0" readonly
                                class="w-full py-3 pl-12 pr-4 text-xl font-bold text-right text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg cursor-not-allowed focus:outline-none transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Uang Diterima</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 font-semibold text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="text" id="uang_diterima_display" placeholder="0"
                                {{ !$selectedTransaksi ? 'disabled' : '' }} required
                                class="w-full py-3 pl-12 pr-4 text-xl font-bold text-right text-green-700 dark:text-green-400 bg-white dark:bg-gray-900 border border-green-400 dark:border-green-600/50 rounded-lg focus:outline-none focus:border-green-600 dark:focus:border-green-400 focus:ring-1 focus:ring-green-600 dark:focus:ring-green-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <input type="hidden" name="uang_diterima" id="uang_diterima" value="0">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Kembalian</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 font-semibold text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="text" id="uang_kembalian" placeholder="0" readonly
                                class="w-full py-2.5 pl-12 pr-4 text-lg font-bold text-right text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700/50 rounded-lg cursor-not-allowed focus:outline-none transition-colors">
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-700">

                    <button type="submit" {{ !$selectedTransaksi ? 'disabled' : '' }}
                        class="w-full px-6 py-3.5 text-sm font-bold text-white transition-colors bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        PROSES PEMBAYARAN
                    </button>

                </form>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script Hilangkan Alert
            setTimeout(() => {
                const alerts = document.querySelectorAll('#alert-success, #alert-error');
                alerts.forEach(alert => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);

            // Script Hitung Kembalian
            const inputBayar = document.getElementById('uang_diterima');
            const inputTagihan = document.getElementById('total_tagihan');
            const inputKembalian = document.getElementById('uang_kembalian');

            function cleanNumber(rupiahString) {
                let cleanString = rupiahString.replace(/[^0-9]/g, '');
                return parseInt(cleanString) || 0;
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            const inputBayarDisplay = document.getElementById('uang_diterima_display');
            const inputBayarHidden = document.getElementById('uang_diterima');

            if (inputBayarDisplay) {
                inputBayarDisplay.addEventListener('input', function(e) {
                    let nilaiBayar = cleanNumber(this.value);
                    let nilaiTagihan = cleanNumber(inputTagihan.value);

                    // Update hidden input dengan nilai asli (tanpa format)
                    inputBayarHidden.value = nilaiBayar;

                    if (nilaiBayar > 0) {
                        this.value = formatRupiah(nilaiBayar);
                    } else {
                        this.value = '';
                    }

                    let kembalian = nilaiBayar - nilaiTagihan;

                    if (kembalian > 0) {
                        inputKembalian.value = formatRupiah(kembalian);
                    } else {
                        inputKembalian.value = "0";
                    }
                });
            }
        });
    </script>
@endsection
