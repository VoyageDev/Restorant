@extends('Admin')

@section('pageTitle', 'Pendapatan Dan Analitik')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6">

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-blue-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Rp
                    {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                <p
                    class="mt-2 text-xs font-medium {{ $perubahanHarian >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                    {!! $perubahanHarian >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($perubahanHarian) }}% dari kemarin
                </p>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-green-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Minggu Ini</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Rp
                    {{ number_format($pendapatanMingguIni, 0, ',', '.') }}</p>
                <p
                    class="mt-2 text-xs font-medium {{ $perubahanMingguan >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                    {!! $perubahanMingguan >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($perubahanMingguan) }}% dari minggu lalu
                </p>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-l-4 border-l-purple-500 transition-colors">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Bulan Ini</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Rp
                    {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                <p
                    class="mt-2 text-xs font-medium {{ $perubahanBulanan >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                    {!! $perubahanBulanan >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($perubahanBulanan) }}% dari bulan lalu
                </p>
            </div>
        </div>

        <div
            class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm transition-colors">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Grafik Trend Pendapatan</h3>

                <select onchange="window.location.href='?periode=' + this.value"
                    class="px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                    <option value="7_hari" {{ request('periode', '7_hari') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir
                    </option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div class="relative w-full h-72 pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Top Menu Terlaris</h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">Nama Menu</th>
                                <th class="px-3 py-2 text-center">Terjual</th>
                                <th class="px-3 py-2 text-right">Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMenus as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">
                                        {{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                                    <td class="px-3 py-2 text-center text-blue-600 dark:text-blue-400 font-bold">
                                        {{ $item->total_terjual }} Porsi</td>
                                    <td class="px-3 py-2 text-right text-gray-700 dark:text-gray-300">Rp
                                        {{ number_format($item->total_omzet, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="3" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">Belum
                                        ada data penjualan menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Top Meja Favorit</h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">No. Meja</th>
                                <th class="px-3 py-2 text-center">Total Transaksi</th>
                                <th class="px-3 py-2 text-right">Total Tagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMejas as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 font-bold text-gray-900 dark:text-white">Meja
                                        {{ $item->meja->no_meja ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center text-purple-600 dark:text-purple-400 font-bold">
                                        {{ $item->total_transaksi }} Trx</td>
                                    <td class="px-3 py-2 text-right text-gray-700 dark:text-gray-300">Rp
                                        {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="3" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">Belum
                                        ada data transaksi meja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Performa Kasir</h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">Nama Kasir</th>
                                <th class="px-3 py-2 text-center">Trx Diproses</th>
                                <th class="px-3 py-2 text-right">Uang Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($performaKasir as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">
                                        {{ $item->user->name ?? 'User Dihapus' }}</td>
                                    <td class="px-3 py-2 text-center text-green-600 dark:text-green-400 font-bold">
                                        {{ $item->total_transaksi }} Trx</td>
                                    <td class="px-3 py-2 text-right text-gray-700 dark:text-gray-300">Rp
                                        {{ number_format($item->total_uang_masuk, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="3" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">Belum
                                        ada data kasir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm transition-colors">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Performa Waiter</h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 dark:text-gray-300 uppercase border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2">Nama Waiter</th>
                                <th class="px-3 py-2 text-center">Meja Dilayani</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($performaWaiter as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">
                                        {{ $item->waiter_name }}</td>
                                    <td class="px-3 py-2 text-center text-orange-600 dark:text-orange-400 font-bold">
                                        {{ $item->total_meja_dilayani }} Meja</td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="2" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">Belum
                                        ada data waiter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil data dari Controller menggunakan arahan JSON Blade
            const chartData = @json($chartData);

            // Cek apakah mode saat ini adalah dark mode (untuk menyesuaikan warna tulisan grafik)
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#e5e7eb' : '#374151'; // gray-200 : gray-700
            const gridColor = isDarkMode ? '#374151' : '#f3f4f6'; // gray-700 : gray-100

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels, // Data dinamis dari database
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: chartData.data, // Data dinamis dari database
                        backgroundColor: '#3b82f6',
                        hoverBackgroundColor: '#2563eb',
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    color: textColor, // Warna teks dinamis
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000) + ' Jt';
                                    } else if (value >= 1000) {
                                        return (value / 1000) + ' Rb';
                                    }
                                    return value;
                                }
                            },
                            grid: {
                                color: gridColor,
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
