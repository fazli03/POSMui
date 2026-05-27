<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 12px;
        }

        td[colspan] {
            border-right: none;
            /* hilangkan garis kanan jika ingin lebih bersih */
        }

        th {
            background-color: #f5f5f5;
            color: #333;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-style: italic;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>

<body>

    <h2>Laporan Penjualan</h2>

    @php
        $grandTotal = $orders->where('status', 'selesai')->sum('total');
    @endphp

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th>Kode Pesanan</th>
                <th class="text-right">Total</th>
                <th class="text-center">Metode</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $i => $order)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $order->created_at->format('d-m-Y') }}</td>
                    <td>{{ $order->kode_pesanan }}</td>
                    <td class="text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ ucwords(str_replace('_', ' ', $order->metode_bayar)) }}
                    </td>

                    <td class="text-center">{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Total Keseluruhan</td>
                <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>

    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
    </div>

</body>

</html>
