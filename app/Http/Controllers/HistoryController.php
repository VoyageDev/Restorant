<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua transaksi yang sudah dibayar (status = 'paid')
        // dengan relasi: meja, user (yang buat order), detailTransactions.menu, payment
        $query = Transaction::with(['meja', 'user', 'detailTransactions.menu', 'payment.user'])
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        // Filter berdasarkan pencarian no_trx jika ada
        if ($request->filled('search')) {
            $query->where('no_trx', 'like', '%'.$request->search.'%');
        }

        $transactions = $query->get();

        // Hitung subtotal, pajak, dan grand total untuk setiap transaksi
        foreach ($transactions as $trx) {
            $trx->subtotal = $trx->detailTransactions->sum('subtotal');
            $trx->pajak = $trx->subtotal * 0.10;
            $trx->grandTotal = $trx->subtotal + $trx->pajak;

            // Hitung kembalian jika ada payment
            if ($trx->payment) {
                $trx->kembalian = $trx->payment->amount - $trx->grandTotal;
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'transactions' => $transactions,
            ]);
        }

        return view('components.history', compact('transactions'));
    }
}
