@extends('layouts.pelanggan')

@section('title', 'menu')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
@endsection

@section('content')

<div class="cart-header">
    <div class="back-button" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </div>
    <h2>Data Pemesanan</h2>
</div>

<div class="form-container">
    <form action="{{ route('cart.prosesPembayaran') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama">Nama Pelanggan</label>
            <input type="text" name="nama" id="nama" required>
        </div>

        {{-- Tampilkan nomor meja hanya jika bukan takeaway --}}
        @if (session('tipe_order') !== 'takeaway')
            <div class="form-group">
                <label for="no_meja">Nomor Meja</label>
                <select name="no_meja" id="no_meja" class="select2-grid" required>
                    <option value="">Pilih Nomor Meja</option>
                    @for ($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ old('no_meja') == $i ? 'selected' : '' }}>
                            <span>{{ $i }}</span>
                        </option>
                    @endfor
                </select>
                @error('no_meja')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="no_meja" value="0">
        @endif

        <div class="form-group">
            <label for="metode_bayar">Metode Pembayaran</label>
            <select name="metode_bayar" id="metode_bayar" class="select2" required>
                <option value="">Pilih Metode</option>
                <option value="tunai">Tunai</option>
                <option value="non_tunai">Non Tunai</option>
            </select>
        </div>

        <button type="submit" class="checkout-btn">Konfirmasi dan Simpan</button>
    </form>

    @if(session('error'))
    <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
        {{ session('error') }}
    </div>
@endif
</div>

@endsection


