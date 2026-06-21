<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Meja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // 1. Statistik Ringkas Hari Ini
        $query = Transaksi::whereDate('created_at', $today);
        $totalPendapatan = $query->sum('total_harga');
        $totalTransaksi = $query->count();
        $jumlahMenu = Menu::count();
        $jumlahMeja = Meja::count();

        // 2. Data Menu Terlaris (Hari ini)
        $menus_terlaris = DetailTransaksi::with('menu')
            ->whereDate('created_at', $today)
            ->select('menu_id', DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('menu_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        // 3. Data Grafik 7 Hari Terakhir
        $rekap_mingguan = Transaksi::where('created_at', '>=', $sevenDaysAgo)
            ->select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan', 'totalTransaksi', 'jumlahMenu', 'jumlahMeja', 
            'menus_terlaris', 'rekap_mingguan'
        ));
    }
}