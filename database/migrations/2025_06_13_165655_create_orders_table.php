<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan');
            $table->string('nama');
            $table->string('no_meja')->nullable();
            $table->enum('tipe_order', ['dine_in', 'takeaway']);
            $table->enum('metode_bayar', ['tunai', 'non_tunai']);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'dibatalkan']);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('total');
            $table->unsignedInteger('jumlah_uang_diberikan');
            $table->unsignedInteger('kembalian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
