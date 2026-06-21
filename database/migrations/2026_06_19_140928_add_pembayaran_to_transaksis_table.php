<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('transaksis', function (Blueprint $table) {
        // Cek apakah kolom sudah ada sebelum menambahkannya agar tidak error
        if (!Schema::hasColumn('transaksis', 'referensi')) {
            $table->string('referensi')->nullable();
        }
        
        if (!Schema::hasColumn('transaksis', 'bayar')) {
            $table->decimal('bayar', 15, 2)->default(0);
        }
    });
}

public function down()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn(['referensi', 'bayar']);
    });
}
};
