<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        // Loop dari 1 sampai 15
        for ($i = 1; $i <= 15; $i++) {
            // str_pad digunakan agar angka 1 menjadi 01, 2 menjadi 02, dst.
            $nomor = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            Meja::create([
                'nomor_meja' => 'Meja ' . $nomor,
            ]);
        }
    }
}