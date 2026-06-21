<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['meja', 'detail.menu']);

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian Berdasarkan ID
        if ($request->filled('search')) {
            $query->where('id', $request->search);
        }

        // UBAH DARI ->get() MENJADI ->paginate(10)
        $transaksis = $query->latest()->paginate(10); 

        // PENTING: Agar filter/pencarian tetap terbawa saat pindah halaman,
        // tambahkan withQueryString() agar parameter URL (seperti search & status) tidak hilang
        $transaksis->withQueryString();

        return view('kasir.riwayat', compact('transaksis'));
    }

    public function bayar($id)
    {
        return redirect()->route('kasir.pembayaran.detail', $id);
    }
}