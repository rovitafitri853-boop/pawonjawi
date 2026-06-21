<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama_kategori'];

    // Tambahkan relasi ini
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}