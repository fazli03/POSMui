<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <div class="header-section">
        <div class="home">Home</div>
    </div>
    <div class="container">
        <!-- Header with Background -->


        <div class="main-content">
            <div class="left-column">
                <!-- Promotion Banner -->
                <div class="mui-logo">
                    <img src="{{ asset('gambar/logomui.png') }}" alt="" class="logo">
                </div>
            </div>
            <div class="right-column">
                <!-- Greeting -->
                <div class="greeting">Pesan Sekarang?</div>

                <!-- Order Options -->
                <div class="order-options">
                    <a href="/menu?tipe=dine_in" class="order-card pickup-card">
                        <h4>Dine In</h4>
                        <div class="order-icon">
                            <i class="fas fa-utensils"></i>
                            <p>Nikmati Makananmu Disini</p>
                        </div>
                    </a>
                    <a href="/menu?tipe=takeaway" class="order-card delivery-card">
                        <h4>Take Away</h4>
                        <div class="order-icon">
                            <i class="fas fa-shopping-bag"></i>
                            <p>Bawa Pulang Pesananmu</p>
                        </div>
                    </a>
                </div>

                <div class="perlu-bantuan-box">
                    <div class="judul">Perlu Bantuan?</div>
                    <div class="subjudul">Customer Service</div>
                    <div class="kontak">
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
