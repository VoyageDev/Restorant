<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Employee;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    //
    public function index()
    {
        // Kirim data Meja, Waiter, dan Menu ke tampilan Blade agar dinamis
        // Tampilkan semua menu karena view sudah handle validasi daily_stock
        $mejas = Meja::whereIn('status', ['Tersedia', 'Reserved'])->get();
        $waiters = Employee::where('jabatan', 'Waiter')->get();
        $menus = Menu::latest()->get();

        if (request()->wantsJson()) {
            return response()->json([
                'mejas' => $mejas,
                'waiters' => $waiters,
                'menus' => $menus,
            ]);
        }

        return view('components.pesanan', compact('mejas', 'waiters', 'menus'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'meja_id' => 'required|exists:meja,id',
            'items' => 'required|json', // Draft pesanan berupa string JSON dari Alpine.js
        ]);

        // Decode JSON menjadi array PHP
        $items = json_decode($request->items, true);

        if (count($items) == 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak boleh kosong!',
                ], 400)->header('Content-Type', 'application/json');
            }

            return back()->with('error', 'Pesanan tidak boleh kosong!');
        }

        // VALIDASI: Cek apakah daily_stock cukup untuk semua item
        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id']);

            if (! $menu) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Menu dengan ID '.$item['menu_id'].' tidak ditemukan!',
                    ], 400)->header('Content-Type', 'application/json');
                }

                return back()->with('error', 'Menu dengan ID '.$item['menu_id'].' tidak ditemukan!');
            }

            if ($menu->daily_stock < $item['qty']) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock '.$menu->name.' tidak cukup! Tersedia: '.$menu->daily_stock.', Diminta: '.$item['qty'],
                    ], 400)->header('Content-Type', 'application/json');
                }

                return back()->with('error', 'Stock '.$menu->name.' tidak cukup! Tersedia: '.$menu->daily_stock.', Diminta: '.$item['qty']);
            }
        }

        // Mulai Transaksi Database (agar aman jika ada error di tengah jalan)
        DB::beginTransaction();

        try {
            // 1. Buat Nomor Transaksi Unik (Contoh: TRX-20260226-0001)
            $no_trx = 'TRX-'.date('Ymd').'-'.rand(1000, 9999);

            // Ambil nama waiter dan tipe order dari item pertama (karena ini pesanan 1 meja)
            $waiterName = $items[0]['waiter_name'];
            $orderType = $items[0]['order_type'];

            // 2. Simpan ke tabel 'transactions'
            $transaction = Transaction::create([
                'users_id' => Auth::id() ?? 1, // Anggap 1 jika belum login untuk testing
                'meja_id' => $request->meja_id,
                'no_trx' => $no_trx,
                'waiter_name' => $waiterName, // Sesuai kolom di db
                'order_type' => $orderType, // Sesuai enum 'dine_in','take_away' di db
                'status' => 'ordered',
            ]);

            // 3. Simpan setiap item ke tabel 'detail_transactions' dan kurangi daily_stock
            foreach ($items as $item) {
                DetailTransaction::create([
                    'transaction_id' => $transaction->id, // Hubungkan dengan transaksi induk
                    'menu_id' => $item['menu_id'],
                    'jumlah_pesanan' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                    'note' => $item['note'],
                ]);

                // Kurangi daily_stock
                $menu = Menu::find($item['menu_id']);
                $newDailyStock = $menu->daily_stock - $item['qty'];

                // Update status menu menjadi 'Habis' jika daily_stock = 0
                $status = ($newDailyStock <= 0) ? 'Habis' : 'Tersedia';

                $menu->update([
                    'daily_stock' => max(0, $newDailyStock), // Pastikan tidak negatif
                    'status' => $status,
                ]);
            }

            // 4. Update status meja menjadi 'Terisi' (Jika dine-in)
            if ($orderType == 'dine_in') {
                Meja::where('id', $request->meja_id)->update(['status' => 'Terisi']);
            }

            // Simpan semua ke DB secara permanen
            DB::commit();

            // Return success response
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan Meja berhasil disimpan dan stock menu telah dikurangi!',
                    'transaction_id' => $transaction->id,
                    'no_trx' => $no_trx,
                ], 200)->header('Content-Type', 'application/json');
            }

            return redirect()->route('pesanan')->with('success', 'Pesanan Meja berhasil disimpan dan stock menu telah dikurangi!');

        } catch (\Exception $e) {
            // Batalkan semua penyimpanan jika terjadi error (Rollback)
            DB::rollBack();

            $errorMsg = 'Gagal menyimpan pesanan: '.$e->getMessage();

            // Jika request AJAX, return JSON error
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 400)->header('Content-Type', 'application/json');
            }

            return back()->with('error', $errorMsg);
        }
    }
}
