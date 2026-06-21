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
    Schema::create('menus', function (Blueprint $table) {
        $table->id();
        // Menghubungkan menu dengan kategori
        $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
        
        $table->string('nama_menu');
        $table->integer('harga');
        
        // Opsional: tambahkan ini jika ingin menambah fitur deskripsi atau foto
        // $table->text('deskripsi')->nullable();
        // $table->string('foto')->nullable();
        
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('menus');
}
};
