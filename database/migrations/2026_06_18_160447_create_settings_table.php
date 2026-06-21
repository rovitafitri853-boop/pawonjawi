<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko'); // Menyimpan nama toko
            $table->text('alamat');      // Menyimpan alamat toko
            $table->timestamps();        // Kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi (jika ingin menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};