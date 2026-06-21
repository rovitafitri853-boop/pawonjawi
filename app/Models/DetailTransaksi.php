<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $guarded = ['id'];

    // Relasi: Detail ini milik satu Transaksi induk
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi: Detail ini merujuk ke satu Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}