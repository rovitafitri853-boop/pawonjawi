<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['nama_menu', 'harga', 'kategori_id'];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
}