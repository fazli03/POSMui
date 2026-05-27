<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        .product-detail-page {
            animation: fadeIn 0.3s ease-out;
            display: flex;

        }

        .product-image-wrapper {
            width: 70%;
            height: 100vh;
            /* atau ukuran tetap sesuai desain */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* biar gambar yang lebih besar terpotong rapi */
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* atau 'contain' kalau mau semua gambar full tanpa crop */
        }

        .produk-info {
            width: 30%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }


        .product-detail-page h3 {
            padding: 0 24px;
            margin: 24px 0 12px;
            font-size: 26px;
            font-weight: 600;
            color: #222;
            line-height: 1.4;
            letter-spacing: 0.5px;
        }

        .product-detail-page p {
            padding: 0 24px;
            margin: 0px 0;
            font-size: 15.5px;
            color: #555;
            line-height: 1.6;
        }

        .product-detail-page .product-price {
            color: #4caf50;
            font-size: 18px;
            font-weight: 500;
        }

        .product-detail-page .product-description {
            color: #777;
            text-align: justify;
            font-size: 15px;
            letter-spacing: 0.3px;
        }

        .product-bottom-wrapper {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 16px 0px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0;
            box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.08);
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            flex-wrap: wrap;
            transition: all 0.3s ease;
        }

        .quantity-section {
            display: flex;
            align-items: center;
            gap: 0px;
            padding: 6px;
            border-radius: 12px;
            margin-left: 15px;
        }

        .quantity-section button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #FE5D26;
            border: 1px solid #e9ecef;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            margin: -10px
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }


        #decreaseQty {
            background: rgba(255, 185, 35, 0);
            border: 1px solid #FE5D26;
            color: #FE5D26;
        }

        .quantity-section button:hover {
            background: #FE5D26;
            border-color: #FE5D26;
            transform: translateY(-1px);
        }

        .quantity-section button:active {
            transform: translateY(0);
        }

        #quantityInput {
            width: 40px;
            height: 40px;
            text-align: center;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            color: #212529;
            outline: none;
        }

        #addToCartBtn {
            padding: 14px 32px;
            background: #FE5D26;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-right: 10px;
        }

        #addToCartBtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        #addToCartBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(40, 167, 69, 0.4);
        }

        #addToCartBtn:hover::before {
            left: 100%;
        }

        #addToCartBtn:active {
            transform: translateY(0);
            box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);
        }

        @media (max-width: 480px) {
            .product-detail-page {
                animation: fadeIn 0.3s ease-out;
                display: flex;
                flex-direction: column
            }

            .product-image-wrapper {
                width: 100%;
                height: auto;
                /* biar mobile fleksibel */
            }

            .product-image-wrapper img {
                border-radius: 0 0 30px 30px;
                height: auto;
            }

            .product-detail-page h3 {
                font-size: 20px;
                padding: 0 20px;
            }

            .product-detail-page p {
                padding: 0 20px;
                font-size: 14px;
            }

            .produk-info {
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .quantity-section button {
                width: 36px;
                height: 36px;
            }

            #quantityInput {
                width: 50px;
                height: 36px;
                font-size: 16px;
            }

            #addToCartBtn {
                padding: 12px 24px;
                font-size: 15px;
            }

            .product-bottom-wrapper {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 16px 0px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0;
                box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.08);
                border-top: 1px solid rgba(0, 0, 0, 0.06);
                flex-wrap: wrap;
                transition: all 0.3s ease;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }


        .back-button {
            position: fixed;
            top: 16px;
            left: 16px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }

        .back-button:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="back-button" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </div>

    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="product-detail-page">
            <div class="product-image-wrapper">
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}">
            </div>
            <div class="produk-info">
                <div>
                    <h3>{{ $produk->nama }}</h3>
                    {{-- <p class="product-category">{{ $produk->kategori->nama }}</p> --}}
                    <p class="product-price">Rp. {{ number_format($produk->harga, 0) }}</p>
                    <p class="product-description">{!! $produk->deskripsi !!}</p>


                    <input type="hidden" name="id" value="{{ $produk->id }}">
                    <input type="hidden" name="nama" value="{{ $produk->nama }}">
                    <input type="hidden" name="harga" value="{{ $produk->harga }}">
                    <input type="hidden" name="gambar" value="{{ $produk->gambar }}">
                    <input type="hidden" name="qty" id="qtyInputField" value="1">
                </div>
                <div class="product-bottom-wrapper">
                    <div class="quantity-section">
                        <button type="button" id="decreaseQty">−</button>
                        <input type="number" id="quantityInput" value="1" min="1">
                        <button type="button" id="increaseQty">+</button>
                    </div>
                    <button id="addToCartBtn" type="submit" data-unitprice="{{ $produk->harga }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="subtotalPrice">Rp. {{ number_format($produk->harga, 0) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const quantityInput = document.getElementById('quantityInput');
            const subtotalPrice = document.getElementById('subtotalPrice');
            const qtyInputField = document.getElementById('qtyInputField');
            const unitPrice = parseFloat(document.getElementById('addToCartBtn').dataset.unitprice) || 0;

            function updateSubtotal() {
                let qty = parseInt(quantityInput.value);
                if (isNaN(qty) || qty < 1) qty = 1; // minimal 1

                quantityInput.value = qty;
                const subtotal = qty * unitPrice;

                // tampilkan subtotal dalam format rupiah
                subtotalPrice.textContent = `Rp. ${subtotal.toLocaleString('id-ID')}`;

                // update hidden input agar ikut terkirim saat submit
                qtyInputField.value = qty;
            }

            // Tombol -
            decreaseBtn?.addEventListener('click', () => {
                let qty = parseInt(quantityInput.value) || 1;
                if (qty > 1) {
                    qty--;
                    quantityInput.value = qty;
                    updateSubtotal();
                }
            });

            // Tombol +
            increaseBtn?.addEventListener('click', () => {
                let qty = parseInt(quantityInput.value) || 1;
                qty++;
                quantityInput.value = qty;
                updateSubtotal();
            });

            // Ketika input manual
            quantityInput?.addEventListener('input', updateSubtotal);

            // Set qty hidden input sebelum submit
            document.getElementById('addToCartBtn').addEventListener('click', function() {
                qtyInputField.value = quantityInput.value;
            });

            // Jalankan pertama kali
            updateSubtotal();
        });
    </script>

</body>

</html>
