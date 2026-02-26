<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        // ===================================
        // 1. RINGKASAN PENDAPATAN
        // ===================================

        // Pendapatan Hari Ini
        $pendapatanHariIni = $this->hitungPendapatan(Carbon::today(), Carbon::tomorrow());

        // Pendapatan Kemarin (untuk perbandingan)
        $pendapatanKemarin = $this->hitungPendapatan(Carbon::yesterday(), Carbon::today());

        // Hitung persentase perubahan hari ini vs kemarin
        $perubahanHarian = $this->hitungPersentasePerubahan($pendapatanHariIni, $pendapatanKemarin);

        // Pendapatan Minggu Ini (dari hari Senin sampai sekarang)
        $pendapatanMingguIni = $this->hitungPendapatan(Carbon::now()->startOfWeek(), Carbon::now()->endOfDay());

        // Pendapatan Minggu Lalu (untuk perbandingan)
        $pendapatanMingguLalu = $this->hitungPendapatan(
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        );

        $perubahanMingguan = $this->hitungPersentasePerubahan($pendapatanMingguIni, $pendapatanMingguLalu);

        // Pendapatan Bulan Ini
        $pendapatanBulanIni = $this->hitungPendapatan(Carbon::now()->startOfMonth(), Carbon::now()->endOfDay());

        // Pendapatan Bulan Lalu (untuk perbandingan)
        $pendapatanBulanLalu = $this->hitungPendapatan(
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        );

        $perubahanBulanan = $this->hitungPersentasePerubahan($pendapatanBulanIni, $pendapatanBulanLalu);

        // ===================================
        // 2. TOP MENU TERLARIS
        // ===================================
        $topMenus = DetailTransaction::select(
            'menu_id',
            DB::raw('SUM(jumlah_pesanan) as total_terjual'),
            DB::raw('SUM(subtotal) as total_omzet')
        )
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'paid');
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_terjual', 'DESC')
            ->limit(10)
            ->get();

        // ===================================
        // 3. TOP MEJA FAVORIT
        // ===================================
        $topMejas = Transaction::select(
            'meja_id',
            DB::raw('COUNT(*) as total_transaksi'),
            DB::raw('SUM((SELECT SUM(subtotal) FROM detail_transactions WHERE transaction_id = transactions.id)) as total_tagihan')
        )
            ->where('status', 'paid')
            ->with('meja')
            ->groupBy('meja_id')
            ->orderBy('total_transaksi', 'DESC')
            ->limit(10)
            ->get();

        // Hitung pajak dan grand total untuk setiap meja
        foreach ($topMejas as $meja) {
            $subtotal = $meja->total_tagihan;
            $pajak = $subtotal * 0.10;
            $meja->grand_total = $subtotal + $pajak;
        }

        // ===================================
        // 4. PERFORMA KASIR
        // ===================================
        $performaKasir = Payment::select(
            'users_id',
            DB::raw('COUNT(*) as total_transaksi'),
            DB::raw('SUM(amount) as total_uang_masuk')
        )
            ->with('user')
            ->groupBy('users_id')
            ->orderBy('total_transaksi', 'DESC')
            ->limit(10)
            ->get();

        // ===================================
        // 5. PERFORMA WAITER
        // ===================================
        $performaWaiter = Transaction::select(
            'waiter_name',
            DB::raw('COUNT(*) as total_meja_dilayani')
        )
            ->where('status', 'paid')
            ->groupBy('waiter_name')
            ->orderBy('total_meja_dilayani', 'DESC')
            ->limit(10)
            ->get();

        // ===================================
        // 6. DATA GRAFIK (Default: 7 Hari Terakhir)
        // ===================================
        $periode = $request->get('periode', '7_hari'); // 7_hari, bulan_ini, tahun_ini

        $chartData = $this->getChartData($periode);

        if ($request->wantsJson()) {
            return response()->json([
                'pendapatan_hari_ini' => $pendapatanHariIni,
                'perubahan_harian' => $perubahanHarian,
                'pendapatan_minggu_ini' => $pendapatanMingguIni,
                'perubahan_mingguan' => $perubahanMingguan,
                'pendapatan_bulan_ini' => $pendapatanBulanIni,
                'perubahan_bulanan' => $perubahanBulanan,
                'top_menus' => $topMenus,
                'top_mejas' => $topMejas,
                'performa_kasir' => $performaKasir,
                'performa_waiter' => $performaWaiter,
                'chart_data' => $chartData,
                'periode' => $periode,
            ]);
        }

        // ===================================
        // KIRIM KE VIEW
        // ===================================
        return view('components.pendapatan', compact(
            'pendapatanHariIni',
            'perubahanHarian',
            'pendapatanMingguIni',
            'perubahanMingguan',
            'pendapatanBulanIni',
            'perubahanBulanan',
            'topMenus',
            'topMejas',
            'performaKasir',
            'performaWaiter',
            'chartData'
        ));
    }

    /**
     * Hitung total pendapatan dalam rentang waktu tertentu
     */
    private function hitungPendapatan($startDate, $endDate)
    {
        $transactions = Transaction::where('status', 'paid')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->with('detailTransactions')
            ->get();

        $totalPendapatan = 0;
        foreach ($transactions as $trx) {
            $subtotal = $trx->detailTransactions->sum('subtotal');
            $pajak = $subtotal * 0.10;
            $totalPendapatan += ($subtotal + $pajak);
        }

        return $totalPendapatan;
    }

    /**
     * Hitung persentase perubahan antara nilai sekarang dan sebelumnya
     */
    private function hitungPersentasePerubahan($nilaiSekarang, $nilaiSebelumnya)
    {
        if ($nilaiSebelumnya == 0) {
            return $nilaiSekarang > 0 ? 100 : 0;
        }

        return round((($nilaiSekarang - $nilaiSebelumnya) / $nilaiSebelumnya) * 100, 1);
    }

    /**
     * Generate data untuk grafik berdasarkan periode
     */
    private function getChartData($periode)
    {
        $labels = [];
        $data = [];

        switch ($periode) {
            case '7_hari':
                // 7 hari terakhir
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D, d M'); // Format: Sen, 25 Feb

                    $pendapatan = $this->hitungPendapatan(
                        $date->startOfDay(),
                        $date->copy()->endOfDay()
                    );

                    $data[] = $pendapatan;
                }
                break;

            case 'bulan_ini':
                // Per hari dalam bulan ini
                $startOfMonth = Carbon::now()->startOfMonth();
                $today = Carbon::today();

                for ($date = $startOfMonth->copy(); $date->lte($today); $date->addDay()) {
                    $labels[] = $date->format('d M');

                    $pendapatan = $this->hitungPendapatan(
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    );

                    $data[] = $pendapatan;
                }
                break;

            case 'tahun_ini':
                // Per bulan dalam tahun ini
                for ($month = 1; $month <= Carbon::now()->month; $month++) {
                    $date = Carbon::create(Carbon::now()->year, $month, 1);
                    $labels[] = $date->format('M Y'); // Format: Jan 2026

                    $pendapatan = $this->hitungPendapatan(
                        $date->copy()->startOfMonth(),
                        $date->copy()->endOfMonth()
                    );

                    $data[] = $pendapatan;
                }
                break;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
