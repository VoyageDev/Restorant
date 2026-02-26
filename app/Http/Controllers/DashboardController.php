<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Meja;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total pendapatan (subtotal + pajak 10%) dari transaksi berstatus paid
        $totalSubtotal = DetailTransaction::whereHas('transaction', function ($query) {
            $query->where('status', 'paid');
        })
            ->sum('subtotal');
        $totalPendapatan = $totalSubtotal + ($totalSubtotal * 0.10);

        // Jumlah transaksi berstatus paid
        $jumlahTransaksi = Transaction::where('status', 'paid')->count();

        // Meja aktif (Terisi atau Reserved)
        $mejaAktif = Meja::whereIn('status', ['Terisi', 'Reserved'])->count();

        // 10 Menu terlaris
        $topMenus = DetailTransaction::select(
            'menu_id',
            DB::raw('SUM(jumlah_pesanan) as total_terjual')
        )
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'paid');
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_terjual', 'DESC')
            ->limit(10)
            ->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with(['meja', 'detailTransactions'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentTransactions as $trx) {
            $subtotal = $trx->detailTransactions->sum('subtotal');
            $pajak = $subtotal * 0.10;
            $trx->grand_total = $subtotal + $pajak;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'total_pendapatan' => $totalPendapatan,
                'jumlah_transaksi' => $jumlahTransaksi,
                'meja_aktif' => $mejaAktif,
                'top_menus' => $topMenus,
                'recent_transactions' => $recentTransactions,
            ]);
        }

        return view('components.dashboard', compact(
            'totalPendapatan',
            'jumlahTransaksi',
            'mejaAktif',
            'topMenus',
            'recentTransactions'
        ));
    }
}
