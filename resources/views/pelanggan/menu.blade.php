@extends('layouts.pelanggan')

@section('title', 'menu')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dinein.css') }}">
@endsection

@section('content')

<div class="header">
    <!-- Kategori Navigasi -->
    <nav class="category-nav">
      

        <form action="{{ route('dine.index') }}" method="GET" class="category-form">
            {{-- Tombol All Menu --}}
            <button type="submit" name="kategori" value="" 
                class="nav-item-btn {{ request('kategori') == null ? 'active' : '' }}">
                {{-- <div class="nav-icon"><i class="fas fa-bars"></i></div> --}}
                <div class="nav-text">All Menu</div>
                <div class="nav-count">{{ $kategori->sum('menus_count') }} Items</div>
            </button>

            {{-- Tombol kategori dari DB --}}
            @foreach ($kategori as $kat)
                <button type="submit" name="kategori" value="{{ $kat->id }}" 
                    class="nav-item-btn {{ request('kategori') == $kat->id ? 'active' : '' }}">
                    {{-- <div class="nav-icon">
                        <i class="{{ $kat->icon }}"></i>
                    </div> --}}
                    <div class="nav-text">{{ $kat->nama }}</div>
                    <div class="nav-count">{{ $kat->menus_count }} Items</div>
                </button>
            @endforeach
            {{-- <div class="pagination-wrapper">
                {{ $produk->withQueryString()->links() }}
            </div> --}}

        </form>
    </nav>
    <!-- Pencarian -->
    <div class="search-container">
        <input type="text" class="search-input" placeholder="Search something sweet on your mind...">
        <button class="search-btn"><i class="fas fa-search"></i></button>
    </div>
</div>


<div class="container">
    <!-- Menu Grid -->
    <div class="menu-grid">
        <!-- Row 1 -->
        @foreach ($produk as $item)
            <div class="menu-item {{ $item->is_tersedia ? '' : 'disabled' }}"
                @if($item->is_tersedia)
                    onclick="window.location.href='/info/{{ $item->id }}?tipe=dine_in'"
                @endif>
                <div class="img-container">
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="item-image">
                    @unless($item->is_tersedia)
                        <div class="item-unavailable">Tidak Tersedia</div>
                    @endunless
                </div>
                <div class="item-info" >
                    <h3 class="item-name">{{ $item->nama }}</h3>
                    <div class="buttom-side">
                        <span class="item-category sandwich">{{ $item->kategori->nama }}</span>
                        <div class="item-price">Rp. {{ number_format($item->harga, 0) }}</div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Track Order Button -->  
        <div class="bot-btn">
            @php
                $order = session('order_id') ? \App\Models\Order::find(session('order_id')) : null;
                $isLocked = $order && in_array($order->status, ['pending']);
            @endphp

            <a href="{{ $isLocked ? '#' : url('/cart') }}" 
            class="track-order-btn {{ $isLocked ? 'disabled' : '' }}" 
            onclick="{{ $isLocked ? 'showOrderError(event)' : '' }}">
                <span class="track-icon" style="margin-right: 5px;"><i class="fas fa-shopping-cart"></i></span>
                <span class="track-text">Order</span>
                <span class="order-count">{{ $totalItems }}</span>
            </a>

            @php
                $order = session('order_id') ? \App\Models\Order::find(session('order_id')) : null;
            @endphp

            @if ($order && in_array($order->status, ['pending', 'diproses']))
                <a href="{{ route('order.finish') }}" class="track-order-btn">
                    <span class="track-icon"><i class="fas fa-receipt"></i></span>
                </a>
            @endif
        </div>
    </div>
</div>

@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    @php
        $types = ['success', 'error', 'warning', 'info'];
        $type = collect($types)->first(fn($t) => session()->has($t));
        $message = session($type);
    @endphp

    <div id="globalToast" class="custom-toast {{ $type }}">
        <span class="toast-message">{{ $message }}</span>
    </div>

    
@endif


<div id="toastError" class="toast-error">Pesanan belum selesai.</div>


<script>
function showOrderError(e) {
    e.preventDefault();
    const toast = document.getElementById('toastError');
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>

<script>
        // Auto-hide toast after 3.5 seconds
        setTimeout(() => {
            document.getElementById('globalToast')?.classList.add('show');
        }, 100); // slight delay to allow rendering

        setTimeout(() => {
            document.getElementById('globalToast')?.classList.remove('show');
        }, 3500);
    </script>


<script>
// Add interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Category navigation
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            navItems.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    
    searchBtn.addEventListener('click', function() {
        const searchTerm = searchInput.value.toLowerCase();
        filterMenuItems(searchTerm);
    });

    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.toLowerCase();
            filterMenuItems(searchTerm);
        }
    });

    function filterMenuItems(searchTerm) {
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            const itemName = item.querySelector('.item-name').textContent.toLowerCase();
            const itemCategory = item.querySelector('.item-category').textContent.toLowerCase();
            
            if (itemName.includes(searchTerm) || itemCategory.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = searchTerm === '' ? 'block' : 'none';
            }
        });
    }

    // Menu item click effects
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>
@endsection

