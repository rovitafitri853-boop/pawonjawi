<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller; 
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index(Request $request)
{
    $today = now();
    $status = $request->query('status'); // Mengambil filter dari URL

    $stats = [
        'jumlah_pesanan' => Transaksi::whereDate('created_at', $today)->count(),
        'pending'        => Transaksi::where('status', 'pending')->count(),
        'selesai'        => Transaksi::where('status', 'lunas')
                                     ->whereDate('updated_at', $today)
                                     ->count(),
    ];

    // Tabel 1: Selalu menampilkan yang Pending
    $pendingOrders = Transaksi::where('status', 'pending')
                              ->latest()
                              ->take(5)
                              ->get();
                                    
    // Tabel 2: Riwayat yang Dinamis sesuai klik kartu
    $recentHistoryQuery = Transaksi::query();

    if ($status === 'lunas') {
        // Jika kartu Selesai diklik, tampilkan hanya lunas
        $recentHistoryQuery->where('status', 'lunas');
    } else {
        // Default atau jika tidak ada status, tampilkan riwayat umum (bisa lunas atau lainnya)
        $recentHistoryQuery->whereIn('status', ['lunas', 'batal']); 
    }

    $recentHistory = $recentHistoryQuery->latest('updated_at')
                                        ->take(5)
                                        ->get();

    return view('kasir.dashboard', compact('stats', 'pendingOrders', 'recentHistory'));
}
}