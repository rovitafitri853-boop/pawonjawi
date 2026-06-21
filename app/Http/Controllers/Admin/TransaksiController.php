<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        // Menampilkan daftar transaksi yang sudah terjadi
        $transaksis = Transaksi::latest()->get();
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // Menampilkan form untuk memilih menu
        $menus = Menu::all();
        return view('admin.transaksi.create', compact('menus'));
    }

    public function store(Request $request)
    {
        // Logika menyimpan transaksi
        $request->validate([
            'total_harga' => 'required',
        ]);

        Transaksi::create([
            'user_id' => Auth::id(), // ID kasir yang login
            'total_harga' => $request->total_harga,
            'status' => 'selesai',
        ]);

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }
    public function riwayat()
{
    // Mengambil transaksi yang sudah selesai
    $transaksis = \App\Models\Transaksi::where('status', 'selesai')->get();
    return view('kasir.riwayat', compact('transaksis'));
}
}