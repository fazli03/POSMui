<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #1f2937;
            width: 80mm;
            margin: 0 auto;
            padding: 6mm;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .receipt {
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .receipt::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4, #10b981);
        }

        .header {
            text-align: center;
            padding: 16px 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .company-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .company-info {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .section {
            padding: 12px;
            text-align: center;
        }

        .order-info {
            background: #f8fafc;
            font-size: 9px;
            text-align: left;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .row span:first-child {
            font-weight: 500;
            color: #475569;
        }

        .row span:last-child {
            font-weight: 600;
            color: #1e293b;
        }

        .items {
            background: white;
            text-align: left;
        }

        .items-title {
            text-align: center;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item {
            margin-bottom: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 10px;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
            margin-left: 6px;
        }

        .item-detail .price {
            font-weight: 600;
            color: #059669;
        }

        .total-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            text-align: left;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 8px 0;
        }

        .grand-total {
            background: white;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.1);
            text-align: center;
        }

        .grand-total .row span {
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }

        .payment-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
        }

        .footer {
            text-align: center;
            padding: 14px 12px;
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            color: white;
            font-size: 9px;
        }

        .footer-main {
            font-weight: 500;
            margin-bottom: 4px;
            font-size: 10px;
        }

        .footer-sub {
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .print-time {
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 8px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .receipt {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <div class="company-name">{{ $company['name'] }}</div>
            <div class="company-info">{{ $company['address'] }}</div>
            <div class="company-info">Telp: {{ $company['phone'] }}</div>
        </div>

        <div class="section order-info">
            <div class="row">
                <span>No. Pesanan</span>
                <span>{{ $order->kode_pesanan }}</span>
            </div>
            <div class="row">
                <span>Tanggal</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span>Kasir</span>
                <span>{{ $order->user->name ?? 'Admin' }}</span>
            </div>
            <div class="row">
                <span>Customer</span>
                <span>{{ $order->nama }}</span>
            </div>
            <div class="row">
                <span>Tipe Order</span>
                <span>{{ strtoupper(str_replace('_', ' ', $order->tipe_order)) }}</span>
            </div>
            @if ($order->tipe_order === 'dine_in')
                <div class="row">
                    <span>No. Meja</span>
                    <span>{{ $order->no_meja ?? '-' }}</span>
                </div>
            @endif
        </div>

        <div class="section items">
            <div class="items-title">Detail Pesanan</div>
            <div class="divider"></div>

            @foreach ($order->orderDetails as $detail)
                <div class="item">
                    <div class="item-name">{{ $detail->menu->nama }}</div>
                    <div class="item-detail">
                        <span>{{ $detail->quantity }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                        <span class="price">Rp
                            {{ number_format($detail->quantity * $detail->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="section total-section">
            <div class="row">
                <span>Total Qty</span>
                <span>{{ $order->quantity }} item</span>
            </div>
            <div class="divider"></div>

            <div class="grand-total">
                <div class="row">
                    <span>TOTAL BAYAR</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="payment-box">
                <div class="row">
                    <span>Uang Yang Diberikan</span>
                    <span> Rp
                        {{ number_format($order->metode_bayar === 'non_tunai' ? $order->total : $order->jumlah_uang_diberikan, 0, ',', '.') }}</span>
                </div>
                <div class="row">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($order->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-main">🙏 Terima kasih atas kunjungan Anda!</div>
            <div class="footer-sub">Selamat menikmati makanan Anda</div>
            <div class="print-time">
                Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();

        window.onafterprint = function() {
            window.location.href = "/mui/kasir/orders";
        };
    });
</script>



</html>
