<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua transaksi yang statusnya masih 'ordered' (belum dibayar)
        // Load relasi 'meja' agar kita bisa memunculkan nomor meja di dropdown
        $transaksiAktif = Transaction::with('meja')
            ->where('status', 'ordered')
            ->get();

        // Siapkan variabel kosong untuk struk
        $selectedTransaksi = null;
        $subtotal = 0;
        $pajak = 0;
        $grandTotal = 0;

        // ika Kasir memilih meja di dropdown, URL akan mengirimkan ?trx_id=...
        if ($request->has('trx_id') && $request->trx_id != '') {

            // Ambil data transaksi spesifik beserta detail menu-nya
            // Pastikan relasi di model Transaction bernama 'detailTransactions' dan terhubung ke 'menu'
            $selectedTransaksi = Transaction::with(['meja', 'detailTransactions.menu'])
                ->where('id', $request->trx_id)
                ->where('status', 'ordered')
                ->first();

            // Jika transaksi ditemukan, hitung total harga
            if ($selectedTransaksi) {
                foreach ($selectedTransaksi->detailTransactions as $detail) {
                    $subtotal += $detail->subtotal;
                }

                // Hitung Pajak 10%
                $pajak = $subtotal * 0.10;

                // Hitung Grand Total
                $grandTotal = $subtotal + $pajak;
            }
        }

        // 4. Kirim semua data ke tampilan pembayaran
        if ($request->wantsJson()) {
            return response()->json([
                'transaksi_aktif' => $transaksiAktif,
                'selected_transaksi' => $selectedTransaksi,
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'grand_total' => $grandTotal,
            ]);
        }

        return view('components.pembayaran', compact(
            'transaksiAktif',
            'selectedTransaksi',
            'subtotal',
            'pajak',
            'grandTotal'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validasi input uang diterima
        $request->validate([
            'uang_diterima' => 'required|numeric|min:0',
        ]);

        // Cari transaksi berdasarkan ID dari URL
        $transaction = Transaction::with(['meja', 'detailTransactions'])->findOrFail($id);

        // Hitung total pembayaran
        $subtotal = $transaction->detailTransactions->sum('subtotal');
        $pajak = $subtotal * 0.10;
        $grandTotal = $subtotal + $pajak;

        // Ambil uang diterima dari form (sudah dalam format angka tanpa pemisah)
        $uangDiterima = $request->uang_diterima;

        // Validasi: Uang diterima harus >= Grand Total
        if ($uangDiterima < $grandTotal) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Uang diterima kurang dari total tagihan!',
                ], 422);
            }

            return redirect()->back()->with('error', 'Uang diterima kurang dari total tagihan!');
        }

        // Gunakan transaction database untuk memastikan konsistensi data
        DB::beginTransaction();

        try {
            // 1. Buat record pembayaran di tabel payment
            $payment = Payment::create([
                'transaction_id' => $transaction->id,
                'amount' => $uangDiterima, // Simpan uang yang diterima dari customer
                'paid_at' => now(),
                'users_id' => Auth::id(),
            ]);

            // 2. Ubah status transaksi menjadi 'paid'
            $transaction->update(['status' => 'paid']);

            // 3. Kosongkan kembali mejanya (Jika Dine-in)
            if ($transaction->order_type == 'dine_in' && $transaction->meja) {
                $transaction->meja->update(['status' => 'Tersedia']);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Pembayaran berhasil.',
                    'payment' => $payment,
                    'transaction' => $transaction,
                ]);
            }

            // Kembali ke halaman pembayaran dengan pesan sukses
            return redirect()->route('pembayaran')->with('success', 'Pembayaran berhasil! Meja telah dikosongkan dan pembayaran tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Gagal memproses pembayaran: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('pembayaran')->with('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }
}
