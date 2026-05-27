<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amazing Treats - Food Delivery</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    
    @yield('head')
        
</head>
<body>
    <div class="container">
        <!-- Header with Background -->
        <div class="header-section">
           
        </div>

        @yield('content')

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <a href="#" class="nav-item active" onclick="setActiveNav(this)">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
           
            <a href="#" class="nav-item" onclick="setActiveNav(this)">
                <i class="fas fa-receipt"></i>
                <span>Pesanan</span>
            </a>
         
        </div>

        <div class="content-spacer"></div>
    </div>

    <script>
        function setActiveNav(element) {
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked item
            element.classList.add('active');
            
            // Prevent default link behavior
            event.preventDefault();
        }

        function showPointsAlert() {
            alert('🎉 Tukar Poin Berhasil!\n\nAnda memiliki 5 poin yang dapat ditukar dengan:\n• Diskon 10% untuk pembelian berikutnya\n• Minuman gratis\n• Upgrade ke premium delivery');
        }

        function selectOrder(type) {
            if (type === 'pickup') {
                alert('🏪 Pick Up dipilih!\n\nSilakan pilih menu favorit Anda.\nPesanan akan siap dalam 15 menit.');
            } else {
                alert('🏍️ Delivery dipilih!\n\nEstimasi waktu pengiriman: 25-30 menit\nOngkir gratis untuk pembelian di atas Rp 50K');
            }
        }

        function shareReward() {
            alert('🎁 Share The Sip!\n\nKode referral Anda: ADAM2025\n\nBagikan ke teman dan dapatkan:\n• Poin bonus setiap referral berhasil\n• Diskon khusus untuk Anda berdua');
        }

        function showPlan() {
            alert('📅 MyFore Plan\n\nPaket berlangganan tersedia:\n• Basic Plan - Rp 29K/bulan\n• Premium Plan - Rp 49K/bulan\n• VIP Plan - Rp 79K/bulan\n\nDapatkan benefit eksklusif!');
        }

        // Pagination dots animation
        setInterval(() => {
            const dots = document.querySelectorAll('.dot');
            const activeDot = document.querySelector('.dot.active');
            const currentIndex = Array.from(dots).indexOf(activeDot);
            const nextIndex = (currentIndex + 1) % dots.length;
            
            activeDot.classList.remove('active');
            dots[nextIndex].classList.add('active');
        }, 3000);

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>
</html>