<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'mejas';

    protected $fillable = ['nomor_meja'];

    // TAMBAHKAN INI: Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // TAMBAHKAN INI: Fungsi untuk cek status meja
    public function isTerisi()
    {
        // Mengecek apakah ada transaksi dengan status 'belum bayar' di meja ini
        return $this->transaksis()->where('status', 'belum bayar')->exists();
    }
}