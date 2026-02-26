<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $trx = Transaction::with(['meja', 'detailTransactions.menu', 'payment.user'])
            ->findOrFail($id);

        $subtotal = $trx->detailTransactions->sum('subtotal');
        $pajak = $subtotal * 0.10;
        $grandTotal = $subtotal + $pajak;
        $kembalian = $trx->payment ? ($trx->payment->amount - $grandTotal) : 0;

        $trx->subtotal = $subtotal;
        $trx->pajak = $pajak;
        $trx->grand_total = $grandTotal;
        $trx->kembalian = $kembalian;

        $pdf = Pdf::loadView('invoice.pdf', compact('trx'))
            ->setPaper('A5', 'portrait');

        return $pdf->download('invoice-'.$trx->no_trx.'.pdf');
    }
}
