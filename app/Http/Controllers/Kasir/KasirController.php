<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    // Aksi saat klik tombol Simpan
    public function simpanPending(Request $request) 
    {
        $transaksi = Transaksi::findOrFail($request->transaksi_id);
        $transaksi->update(['status' => 'pending']);
        return redirect()->route('kasir.dashboard')->with('success', 'Pesanan disimpan!');
    }

    // Aksi saat klik tombol Batal
    public function batal($id) 
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('kasir.dashboard')->with('success', 'Pesanan dihapus.');
    }
}