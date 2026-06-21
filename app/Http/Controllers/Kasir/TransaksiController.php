<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index($meja_id = null)
    {
        $meja = $meja_id ? Meja::find($meja_id) : null;
        $kategoris = Kategori::with('menus')->get();

        $mejaTerpakai = Transaksi::where('status', 'pending')
            ->whereNotNull('meja_id')
            ->pluck('meja_id')
            ->toArray();

        $transaksi = Transaksi::where('status', 'pending')
            ->where(function($query) use ($meja_id) {
                if ($meja_id) {
                    $query->where('meja_id', $meja_id);
                } else {
                    $query->whereNull('meja_id');
                }
            })
            ->with('detail.menu')
            ->first();
        
        return view('kasir.pos-input', compact('meja', 'kategoris', 'transaksi', 'mejaTerpakai'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with(['detail.menu', 'meja'])->findOrFail($id);
        $kategoris = Kategori::with('menus')->get();
        return view('kasir.pos-edit', compact('transaksi', 'kategoris'));
    }

    public function store(Request $request)
{
    $transaksi = \App\Models\Transaksi::find($request->transaksi_id);

    if (!$transaksi) {
        $transaksi = \App\Models\Transaksi::create([
            'user_id'      => \Illuminate\Support\Facades\Auth::id(),
            'tipe_pesanan' => 'dine_in', // DIPAKSA DINE-IN
            'meja_id'      => $request->meja_id,
            'status'       => 'pending',
            'total_harga'  => 0
        ]);
    }

    \App\Models\DetailTransaksi::create([
        'transaksi_id' => $transaksi->id,
        'menu_id'      => $request->menu_id,
        'jumlah'       => 1,
        'subtotal'     => $request->harga
    ]);

    $transaksi->update(['total_harga' => \App\Models\DetailTransaksi::where('transaksi_id', $transaksi->id)->sum('subtotal')]);

    return response()->json(['success' => true]);
}

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $total = $transaksi->detail()->sum('subtotal');
        $transaksi->update(['total_harga' => $total]);
        return redirect()->route('kasir.riwayat')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function simpanPending(Request $request)
    {
        $request->validate(['transaksi_id' => 'required|exists:transaksis,id']);
        return redirect()->route('kasir.riwayat')->with('success', 'Pesanan telah disimpan.');
    }

    public function halamanPembayaran($id)
    {
        $transaksi = Transaksi::with(['detail.menu', 'meja'])->findOrFail($id);
        return view('kasir.pembayaran', compact('transaksi'));
    }

    public function prosesBayar(Request $request, $id)
{
    // 1. Validasi
    $request->validate([
        'metode' => 'required',
        'diterima' => $request->metode === 'tunai' ? 'required|numeric' : 'nullable',
    ]);

    // 2. Update Transaksi
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->update([
        'status' => 'lunas',
        'metode_pembayaran' => $request->metode,
        'bayar' => ($request->metode === 'tunai') ? $request->diterima : $transaksi->total_harga,
    ]);
    
    // 3. Ambil data dari tabel settings
    // Pastikan model Setting sudah di-import di atas (use App\Models\Setting;)
    $setting = \App\Models\Setting::first(); 
    
    // 4. Cek apakah datanya terbaca (Hapus baris ini nanti setelah berhasil)
    // dd($setting); 
    
    return view('kasir.pembayaran-detail', compact('transaksi', 'setting'))->with('print', true);
}

    public function batal(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->detail()->delete(); 
        $transaksi->delete(); 
        return back()->with('success', 'Pesanan berhasil dikosongkan.');
    }

    public function updateItem(Request $request, $id)
    {
        $detail = DetailTransaksi::with('menu')->findOrFail($id);
        $detail->jumlah = ($request->action == 'tambah') ? $detail->jumlah + 1 : max(1, $detail->jumlah - 1);
        $detail->subtotal = $detail->jumlah * $detail->menu->harga;
        $detail->save();

        return response()->json(['success' => true, 'jumlah' => $detail->jumlah, 'subtotal' => number_format($detail->subtotal)]);
    }

    public function destroyItem($id)
    {
        DetailTransaksi::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}