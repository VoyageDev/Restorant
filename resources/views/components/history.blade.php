@extends('Admin')

@section('pageTitle', 'History')

@section('content')
    <div x-data="{ showModal: false, selectedTrx: null }" class="space-y-6">

        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
            <div class="flex flex-col items-center justify-between mb-4 space-y-4 md:flex-row md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Transaksi Selesai</h3>

                <form method="GET" action="{{ route('history') }}"
                    class="flex flex-col w-full space-y-3 md:flex-row md:w-auto md:space-y-0 md:space-x-3">
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg md:w-auto focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">

                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari No. Transaksi..."
                            class="w-full py-2 pl-10 pr-4 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Filter</button>
                    @if (request('date') || request('search'))
                        <a href="{{ route('history') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                    <thead
                        class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">Waktu Selesai</th>
                            <th class="px-4 py-3 font-medium">No. Trx</th>
                            <th class="px-4 py-3 font-medium">Meja</th>
                            <th class="px-4 py-3 font-medium">Waiter</th>
                            <th class="px-4 py-3 font-medium">Kasir</th>
                            <th class="px-4 py-3 font-medium text-right">Total Akhir</th>
                            <th class="px-4 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr
                                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $trx->payment ? $trx->payment->paid_at->format('d M Y, H:i') : $trx->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $trx->no_trx }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">Meja {{ $trx->meja->no_meja }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $trx->waiter_name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $trx->payment && $trx->payment->user ? $trx->payment->user->name : '-' }}
                                </td>
                                <td class="px-4 py-3 font-bold text-right text-gray-900 dark:text-white">
                                    Rp {{ number_format($trx->grandTotal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="showModal = true; selectedTrx = {{ $trx->toJson() }}"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 border border-blue-200 rounded hover:bg-blue-200 transition dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700 dark:hover:bg-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada transaksi yang selesai
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
            x-transition.opacity>

            <div @click.away="showModal = false"
                class="w-full max-w-md p-6 mx-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh]"
                x-transition.scale.origin.bottom>

                <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-3">
                    <h4 class="font-bold text-gray-800 dark:text-white">Detail Transaksi</h4>
                    <div class="flex space-x-2">
                        <a :href="selectedTrx ? '{{ url('/invoice') }}/' + selectedTrx.id + '/download' : '#'"
                            class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition"
                            title="Cetak Ulang Struk">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                        </a>
                        <button @click="showModal = false"
                            class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="selectedTrx" class="font-mono text-sm text-gray-700 dark:text-gray-300">

                    <div class="pb-4 mb-4 text-center border-b-2 border-dashed border-gray-300 dark:border-gray-600">
                        <h2 class="text-lg font-bold tracking-widest text-gray-900 dark:text-white">RESTORAN UTAMA</h2>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jl. Contoh Alamat No. 123, Kota</p>
                    </div>

                    <div class="pb-4 mb-4 border-b-2 border-dashed border-gray-300 dark:border-gray-600">
                        <div class="grid grid-cols-2 gap-y-1">
                            <p>No. Trx</p>
                            <p class="text-right font-bold text-gray-900 dark:text-white"
                                x-text="selectedTrx?.no_trx || '-'"></p>
                            <p>Waktu</p>
                            <p class="text-right"
                                x-text="selectedTrx?.payment?.paid_at ? new Date(selectedTrx.payment.paid_at).toLocaleString('id-ID') : '-'">
                            </p>
                            <p>Meja</p>
                            <p class="text-right font-semibold text-gray-900 dark:text-white"
                                x-text="selectedTrx?.meja ? 'Meja ' + selectedTrx.meja.no_meja : '-'"></p>
                            <p>Waiter</p>
                            <p class="text-right" x-text="selectedTrx?.waiter_name || '-'"></p>
                            <p>Kasir</p>
                            <p class="text-right" x-text="selectedTrx?.payment?.user?.name || '-'"></p>
                        </div>
                    </div>

                    <div class="pb-4 mb-4 border-b-2 border-dashed border-gray-300 dark:border-gray-600 space-y-3">
                        <p class="font-bold text-gray-900 dark:text-white mb-2">DETAIL PESANAN:</p>

                        <template x-for="detail in selectedTrx?.detail_transactions || []" :key="detail.id">
                            <div class="flex justify-between">
                                <div class="w-2/3">
                                    <p class="font-semibold text-gray-900 dark:text-white"
                                        x-text="detail.menu?.name || 'Menu Dihapus'"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <span x-text="detail.jumlah_pesanan"></span> x
                                        Rp <span x-text="parseInt(detail.price).toLocaleString('id-ID')"></span>
                                    </p>
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white text-right w-1/3">
                                    Rp <span x-text="parseInt(detail.subtotal).toLocaleString('id-ID')"></span>
                                </p>
                            </div>
                        </template>
                    </div>

                    <div class="pb-4 mb-4 border-b-2 border-dashed border-gray-300 dark:border-gray-600">
                        <div class="flex justify-between">
                            <p>Subtotal</p>
                            <p class="text-right">Rp <span
                                    x-text="parseInt(selectedTrx?.subtotal || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p>Pajak (10%)</p>
                            <p class="text-right">Rp <span
                                    x-text="parseInt(selectedTrx?.pajak || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                        <div class="flex justify-between mt-3 text-base font-bold text-gray-900 dark:text-white">
                            <p>GRAND TOTAL</p>
                            <p class="text-right">Rp <span
                                    x-text="parseInt(selectedTrx?.grand_total || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <p>Uang Dibayar</p>
                            <p class="text-right">Rp <span
                                    x-text="parseInt(selectedTrx?.payment?.amount || 0).toLocaleString('id-ID')"></span>
                            </p>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400 mt-1">
                            <p>Kembalian</p>
                            <p class="text-right">Rp <span
                                    x-text="parseInt(selectedTrx?.kembalian || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                    </div>

                    <div class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                        <p>Terima kasih atas kunjungan Anda!</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
