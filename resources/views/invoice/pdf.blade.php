<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
        }

        .container {
            width: 80mm;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="center">
            <strong>RESTORAN UTAMA</strong><br>
            Jl. Contoh Alamat No. 123<br>
        </div>

        <div class="line"></div>

        <div class="row">
            <span>No. Trx</span>
            <span>{{ $trx->no_trx }}</span>
        </div>

        <div class="row">
            <span>Waktu</span>
            <span>{{ \Carbon\Carbon::parse($trx->payment->paid_at)->format('d/m/Y H:i') }}</span>
        </div>

        <div class="line"></div>

        <strong>DETAIL PESANAN</strong>

        @foreach ($trx->detailTransactions as $detail)
            <div>
                {{ $detail->menu->name }}<br>
                {{ $detail->jumlah_pesanan }} x Rp {{ number_format($detail->price) }}
                <span class="right">Rp {{ number_format($detail->subtotal) }}</span>
            </div>
        @endforeach

        <div class="line"></div>

        <div class="row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($trx->subtotal) }}</span>
        </div>

        <div class="row">
            <span>Pajak</span>
            <span>Rp {{ number_format($trx->pajak) }}</span>
        </div>

        <div class="row">
            <strong>Total</strong>
            <strong>Rp {{ number_format($trx->grand_total) }}</strong>
        </div>

        <div class="line"></div>

        <div class="row">
            <span>Bayar</span>
            <span>Rp {{ number_format($trx->payment->amount) }}</span>
        </div>

        <div class="row">
            <span>Kembali</span>
            <span>Rp {{ number_format($trx->kembalian) }}</span>
        </div>

        <div class="line"></div>

        <div class="center">
            Terima kasih 🙏
        </div>

    </div>

</body>

</html>
