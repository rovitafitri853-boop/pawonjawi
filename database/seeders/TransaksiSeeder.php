<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan DB::transaction agar jika gagal, data tidak terisi sebagian
        DB::transaction(function () {
            
            // 1. Membuat data transaksi (Header)
            $transaksi = Transaksi::create([
                'user_id'     => 1, // Pastikan ID 1 ada di tabel users
                'meja_id'     => 1, // Pastikan ID 1 ada di tabel mejas
                'total_harga' => 30000,
                'status'      => 'lunas', // Menggunakan status yang benar: 'lunas' atau 'pending'
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // 2. Membuat data detail transaksi (Item Menu)
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'menu_id'      => 1, // Pastikan ID 1 ada di tabel menus
                'jumlah'       => 2,
                'subtotal'     => 30000,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            
        });
    }
}