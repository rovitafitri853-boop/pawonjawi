<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting; 

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Diubah menjadi date('Y-m-d') agar default-nya adalah hari ini
        $tanggal_mulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggal_selesai = $request->input('tanggal_selesai', date('Y-m-d'));

        $query = Transaksi::whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59']);
        
        $total_transaksi = $query->count();
        $total_pendapatan = $query->sum('total_harga');

        $menu_data = DetailTransaksi::with('menu')
            ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
            ->select('menu_id', DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('menu_id')
            ->orderBy('total_qty', 'desc');

        $total_menu_terjual = $menu_data->get()->sum('total_qty');
        $menus_terlaris = $menu_data->take(5)->get();

        $rekap_harian = Transaksi::whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('COUNT(*) as total_transaksi'), 
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.laporan', compact(
            'total_transaksi', 'total_pendapatan', 'total_menu_terjual', 
            'menus_terlaris', 'rekap_harian', 'tanggal_mulai', 'tanggal_selesai'
        ));
    }

    // Fungsi untuk membuka halaman cetak (tampilan web) - TIDAK DIUBAH
    public function cetak(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggal_selesai = $request->input('tanggal_selesai', date('Y-m-d'));

        $rekap_harian = Transaksi::whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(total_harga) as total_pendapatan'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $setting = Setting::first(); 

        return view('admin.laporan_cetak', compact('rekap_harian', 'tanggal_mulai', 'tanggal_selesai', 'setting'));
    }

    // Fungsi untuk download file PDF langsung - TIDAK DIUBAH
    public function download(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggal_selesai = $request->input('tanggal_selesai', date('Y-m-d'));

        $rekap_harian = Transaksi::whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(total_harga) as total_pendapatan'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $setting = Setting::first(); 

        $pdf = Pdf::loadView('admin.laporan_cetak', compact('rekap_harian', 'tanggal_mulai', 'tanggal_selesai', 'setting'));
        return $pdf->download('Laporan_Penjualan_' . $tanggal_mulai . '_sd_' . $tanggal_selesai . '.pdf');
    }
}