<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->nullable()->constrained('mejas')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users');
            
            $table->enum('tipe_pesanan', ['dine-in', 'takeaway']);
            $table->enum('status', ['pending', 'lunas', 'batal'])->default('pending');
            
            // Tambahkan kolom untuk pembayaran
            $table->string('metode_pembayaran')->nullable(); // Cash, QRIS, Transfer
            
            // Perhitungan biaya
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            
            $table->string('nama_pelanggan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};