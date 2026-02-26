@extends('Admin')

@section('pageTitle', 'Dashboard')

@section('content')
    <div class="space-y-6">

        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-blue-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">Rp
                    {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-green-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Transaksi</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $jumlahTransaksi }}</p>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-purple-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Meja Aktif</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $mejaAktif }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm lg:col-span-1 transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">10 Menu Terlaris</h3>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-medium">Nama Menu</th>
                                <th scope="col" class="px-4 py-3 font-medium text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMenus as $item)
                                <tr
                                    class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-blue-600 dark:text-blue-400">
                                        {{ $item->total_terjual }}</td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="2" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada data penjualan menu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm lg:col-span-2 transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Transaksi Terbaru</h3>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-medium">ID Pesanan</th>
                                <th scope="col" class="px-4 py-3 font-medium">Meja</th>
                                <th scope="col" class="px-4 py-3 font-medium">Total Harga</th>
                                <th scope="col" class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $trx)
                                <tr
                                    class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $trx->no_trx }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $trx->meja ? 'Meja ' . $trx->meja->no_meja : '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-200">Rp
                                        {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusLabel = $trx->status === 'paid' ? 'Selesai' : $trx->status;
                                            $statusClass =
                                                $trx->status === 'paid'
                                                    ? 'text-green-800 dark:text-green-200 bg-green-100 dark:bg-green-900'
                                                    : ($trx->status === 'ordered'
                                                        ? 'text-yellow-800 dark:text-yellow-200 bg-yellow-100 dark:bg-yellow-900'
                                                        : 'text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700');
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada transaksi terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
