@extends('layouts.pelanggan')

@section('title', 'Keranjang')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endsection

@section('content')

<div class="cart-header">
    <div class="back-button" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </div>
    <h2>Order</h2>
</div>


    <div class="cart-container">
        @if(count($cart) === 0)
            <div class="cart-empty-message" style="text-align: center; padding: 30px; color: #888;">
                <i class="fas fa-shopping-cart" style="font-size: 40px; margin-bottom: 10px;"></i>
                <p>Keranjang masih kosong.</p>
            </div>
        @else
            <!-- Loop untuk setiap item cart -->
            @foreach ($cart as $item)
            <div class="cart-item">
                <!-- Bagian Kiri: Image, Name, Subtotal -->
                <div class="item-left">
                    <img src="{{ asset('storage/' . $item['gambar']) }}" class="item-image" alt="{{ $item['nama'] }}">
                    <div class="item-info">
                        <p class="item-name">{{ $item['nama'] }}</p>
                        <div class="item-qty-badge-wrapper">
                            <div class="item-qty-badge">×{{ $item['qty'] }}</div>
                        </div>
                        <p class="item-subtotal">Subtotal: Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Bagian Kanan: Quantity Circle & Hidden Controls -->
                <div class="item-right">
                    {{-- Form Update Qty --}}
                    <form method="POST" action="{{ route('cart.update') }}" class="quantity-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                        <input type="hidden" name="qty" class="quantityInput" value="{{ $item['qty'] }}">

                        <button type="button" class="qty-btn decreaseQty" data-id="{{ $item['id'] }}" title="Kurangi jumlah">
                            <i class="fas fa-minus"></i> Kurangi
                        </button>

                        <button type="button" class="qty-btn increaseQty" title="Tambah jumlah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>


                        <button type="submit" class="updateBtn" style="display: none;"></button>
                    </form>

                    {{-- Tombol Hapus --}}
                    <form method="POST" action="{{ route('cart.remove') }}" class="remove-form" data-id="{{ $item['id'] }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                        <button type="submit" class="remove-btn" title="Hapus item dari keranjang">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
            <div class="cart-item-note">
                <form method="POST" action="{{ route('cart.note') }}" class="note-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                    <textarea name="note" data-id="{{ $item['id'] }}" placeholder="Catatan untuk item ini..." rows="2">{{ $item['note'] ?? '' }}</textarea>
                </form>
            </div>
            @endforeach
        @endif
    </div>


    <!-- Fixed Checkout Section -->
    <div class="checkout-fixed">
        <div class="checkout-content">
            <div class="total-section">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-amount">Rp. {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</span>
            </div>

            @if(count($cart) > 0)
                <a href="{{ route('cart.formPembayaran') }}" class="checkout-btn">
                    Lanjutkan
                </a>
            @else
                <button class="checkout-btn" style="background-color: #ccc; cursor: not-allowed;" disabled>
                    Keranjang Kosong
                </button>
            @endif
        </div>
    </div>


    <script>
    document.querySelectorAll('.quantity-form').forEach(form => {
        const decreaseBtn = form.querySelector('.decreaseQty');
        const increaseBtn = form.querySelector('.increaseQty');
        const qtyInput = form.querySelector('.quantityInput');
        const updateBtn = form.querySelector('.updateBtn');
        const itemId = decreaseBtn.dataset.id;
        const removeForm = document.querySelector(`.remove-form[data-id="${itemId}"]`);

        decreaseBtn.addEventListener('click', () => {
            let qty = parseInt(qtyInput.value);
            if (qty > 1) {
                qtyInput.value = qty - 1;
                updateBtn.click();
            } else {
                // Qty == 1 → jalankan form hapus
                removeForm.submit();
            }
        });

        increaseBtn.addEventListener('click', () => {
            let qty = parseInt(qtyInput.value);
            qtyInput.value = qty + 1;
            updateBtn.click();
        });
    });
</script>
<script>
    const debounceNoteSave = {};
    let isSubmitting = false;

    document.querySelectorAll('.cart-item-note textarea').forEach(textarea => {
        const form = textarea.closest('form');
        const id = textarea.dataset.id;
        let originalValue = textarea.value;

        textarea.addEventListener('input', function () {
            const currentValue = this.value;
            
            // Skip jika tidak ada perubahan dari nilai asli
            if (currentValue === originalValue) {
                return;
            }

            // Skip jika sedang dalam proses submit
            if (isSubmitting) {
                return;
            }

            // Hapus timeout sebelumnya
            if (debounceNoteSave[id]) {
                clearTimeout(debounceNoteSave[id]);
            }

            // Buat timeout baru dengan delay lebih lama
            debounceNoteSave[id] = setTimeout(() => {
                // Cek lagi apakah ada perubahan
                if (textarea.value !== originalValue && !isSubmitting) {
                    submitNote(form, id, textarea);
                }
            }, 2000); // Tingkatkan delay menjadi 2 detik
        });

        // Event untuk mendeteksi ketika user berhenti mengetik
        textarea.addEventListener('keydown', function(e) {
            // Jika user menekan Enter + Ctrl/Cmd, langsung submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                if (debounceNoteSave[id]) {
                    clearTimeout(debounceNoteSave[id]);
                }
                if (this.value !== originalValue) {
                    submitNote(form, id, this);
                }
            }
        });

        // Submit ketika textarea kehilangan focus (blur)
        textarea.addEventListener('blur', function() {
            if (debounceNoteSave[id]) {
                clearTimeout(debounceNoteSave[id]);
            }
            if (this.value !== originalValue && !isSubmitting) {
                submitNote(form, id, this);
            }
        });
    });

    function submitNote(form, id, textarea) {
        isSubmitting = true;

        // Gunakan AJAX untuk submit form
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                // Update nilai asli setelah berhasil disimpan
                textarea.dataset.originalValue = textarea.value;
            } else {
                console.error('Gagal menyimpan catatan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            isSubmitting = false;
        });
    }
</script>



@endsection