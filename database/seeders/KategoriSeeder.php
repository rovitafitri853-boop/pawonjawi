<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar kategori sesuai permintaan Anda
        $data = [
            'Nasi',
            'Lauk',
            'Sayur',
            'Sambal',
            'Gorengan',
            'Minuman'
        ];

        foreach ($data as $nama) {
            Kategori::create([
                'nama_kategori' => $nama
            ]);
        }
    }
}