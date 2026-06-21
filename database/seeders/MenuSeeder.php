<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Kategori;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Fungsi helper untuk mencari ID Kategori
        $cat = fn($nama) => Kategori::where('nama_kategori', $nama)->first()->id;

        $menuItems = [
            // Nasi
            ['kategori_id' => $cat('Nasi'), 'nama_menu' => 'Nasi Putih', 'harga' => 5000],
            ['kategori_id' => $cat('Nasi'), 'nama_menu' => 'Nasi Liwet', 'harga' => 10000],
            ['kategori_id' => $cat('Nasi'), 'nama_menu' => 'Nasi Kuning', 'harga' => 12000],
            ['kategori_id' => $cat('Nasi'), 'nama_menu' => 'Nasi Uduk', 'harga' => 12000],

            // Lauk
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ayam Goreng', 'harga' => 15000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ayam Bakar', 'harga' => 15000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ayam Penyet', 'harga' => 15000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Empal Daging', 'harga' => 18000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Lele Goreng', 'harga' => 10000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ikan Goreng', 'harga' => 12000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Semur Ayam', 'harga' => 15000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ikan Bakar', 'harga' => 15000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Ati Ampela Goreng', 'harga' => 5000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Telur Balado', 'harga' => 6000],
            ['kategori_id' => $cat('Lauk'), 'nama_menu' => 'Perkedel Kentang', 'harga' => 3000],

            // Sayur
            ['kategori_id' => $cat('Sayur'), 'nama_menu' => 'Sayur Lodeh', 'harga' => 7000],
            ['kategori_id' => $cat('Sayur'), 'nama_menu' => 'Sayur Asem', 'harga' => 7000],
            ['kategori_id' => $cat('Sayur'), 'nama_menu' => 'Sop Ayam Kampung', 'harga' => 15000],
            ['kategori_id' => $cat('Sayur'), 'nama_menu' => 'Urap Sayur', 'harga' => 6000],
            ['kategori_id' => $cat('Sayur'), 'nama_menu' => 'Oseng Kangkung', 'harga' => 6000],

            // Sambal
            ['kategori_id' => $cat('Sambal'), 'nama_menu' => 'Sambal Terasi', 'harga' => 3000],
            ['kategori_id' => $cat('Sambal'), 'nama_menu' => 'Sambal Kecap', 'harga' => 3000],
            ['kategori_id' => $cat('Sambal'), 'nama_menu' => 'Sambal Ijo', 'harga' => 3000],
            ['kategori_id' => $cat('Sambal'), 'nama_menu' => 'Sambal Tomat', 'harga' => 3000],

            // Gorengan
            ['kategori_id' => $cat('Gorengan'), 'nama_menu' => 'Tahu Isi', 'harga' => 2000],
            ['kategori_id' => $cat('Gorengan'), 'nama_menu' => 'Pisang Goreng', 'harga' => 2000],
            ['kategori_id' => $cat('Gorengan'), 'nama_menu' => 'Tempe Mendoan', 'harga' => 2000],
            ['kategori_id' => $cat('Gorengan'), 'nama_menu' => 'Cireng', 'harga' => 2000],

            // Minuman
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Es Teh / Teh Panas', 'harga' => 5000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Es Jeruk', 'harga' => 6000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Kopi Tubruk', 'harga' => 5000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Wedang Uwuh', 'harga' => 8000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Wedang Jahe', 'harga' => 7000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Es Dawet', 'harga' => 8000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Es Campur', 'harga' => 10000],
            ['kategori_id' => $cat('Minuman'), 'nama_menu' => 'Susu Jahe', 'harga' => 8000],
        ];

        foreach ($menuItems as $item) {
            Menu::create($item);
        }
    }
}