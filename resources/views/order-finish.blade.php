<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Diterima</title>
    <link rel="stylesheet" href="{{ asset('css/orderfinish.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    <div class="container">
        @if (count($orders) > 1)
            <div class="order-container">
                <label class="order-label">Pesanan Lainnya:</label>
                <select id="orderSelect" class="order-select"
                    data-selected="{{ route('order.finish.specific', $order->id) }}">
                    @foreach ($orders as $o)
                        <option value="{{ route('order.finish.specific', $o->id) }}"
                            {{ $o->id == $order->id ? 'selected' : '' }}>
                            {{ substr($o->kode_pesanan, 0, 3) . substr($o->kode_pesanan, -3) }} -
                            {{ \Carbon\Carbon::parse($o->created_at)->format('d M H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="header">
            <div class="logo">
                <i class="fas fa-cash-register"></i>
            </div>
            <h1 class="title">Pesanan Diterima</h1>
            <p class="subtitle">Silahkan lanjutkan pembayaran di meja kasir</p>
        </div>

        <div class="order-card">
            <div class="order-header">
                <div class="order-number">{{ substr($order->kode_pesanan, 0, 4) . substr($order->kode_pesanan, -3) }}
                </div>
                <div class="order-time">{{ \Carbon\Carbon::parse($order->created_at)->format('d M H:i') }}</div>
            </div>

            <div class="order-info">
                <div class="info-nama">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $order->nama }}</div>
                </div>
                <div class="info-meja">
                    <div class="info-label">Meja</div>
                    <div class="info-value">{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="info-status">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ $order->status }}</div>
                </div>
            </div>
            <div class="items-header">
                <i class="fas fa-utensils"></i>
                <span>Pesanan Anda</span>
            </div>
            <div class="order-items">
                @foreach ($orderDetails as $item)
                    <div class="item">
                        <div class="item-left">
                            <div class="item-name">{{ $item->menu->nama }}</div>
                            <div class="item-price">(Rp {{ number_format($item->harga, 0, ',', '.') }})</div>
                            <div class="item-note">
                                @if ($item->catatan)
                                    <br><em>Catatan: {{ $item->catatan }}</em>
                                @endif
                            </div>
                        </div>
                        <div class="item-right">
                            <div class="item-qty">×{{ $item->quantity }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="total-section">
                <div class="total-row">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-value">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <a href="/menu" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Menu
        </a>
    </div>


    <!-- jQuery (wajib sebelum select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS dan JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const $select = $('#orderSelect');

            // Inisialisasi Select2
            $select.select2({
                placeholder: "Pilih pesanan lainnya",
                allowClear: false,
                width: 'resolve',
                minimumResultsForSearch: Infinity

            });

            // Set value yang terpilih berdasarkan atribut data
            const selectedValue = $select.data('selected');
            if (selectedValue) {
                $select.val(selectedValue).trigger('change.select2'); // pakai trigger select2 agar UI terupdate
            }

            // Event ketika select berubah
            $select.on('change', function() {
                const url = $(this).val();
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
</body>

</html>
