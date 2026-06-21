<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id', 
        'meja_id', 
        'status', 
        'tipe_pesanan', 
        'total_harga', 
        'metode_pembayaran', 
        'referensi', 
        'bayar'
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Meja
     */
    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    /**
     * Relasi ke DetailTransaksi
     */
    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}