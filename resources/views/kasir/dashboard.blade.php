<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Latar Belakang Abu-abu --}}
    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Total Pesanan Hari Ini --}}
                <a href="{{ route('kasir.riwayat') }}" class="block bg-white p-6 rounded-2xl shadow-sm border hover:shadow-md transition text-center">
                    <h3 class="text-gray-500 text-base font-semibold mb-2">Total Pesanan Hari Ini</h3>
                    <div class="flex items-center justify-center h-12">
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['jumlah_pesanan'] }}</p>
                    </div>
                </a>
                
                {{-- Filter Pending --}}
                <a href="{{ route('kasir.riwayat', ['status' => 'pending']) }}" class="block bg-white p-6 rounded-2xl shadow-sm border hover:shadow-md transition text-center">
                    <h3 class="text-gray-500 text-base font-semibold mb-2">Total Pesanan Pending</h3>
                    <div class="flex items-center justify-center h-12">
                        <p class="text-3xl font-bold text-orange-500">{{ $stats['pending'] }}</p>
                    </div>
                </a>
                
                {{-- Filter Selesai --}}
                <a href="{{ route('kasir.riwayat', ['status' => 'lunas']) }}" class="block bg-white p-6 rounded-2xl shadow-sm border hover:shadow-md transition text-center">
                    <h3 class="text-gray-500 text-base font-semibold mb-2">Total Pesanan Selesai</h3>
                    <div class="flex items-center justify-center h-12">
                        <p class="text-3xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
                    </div>
                </a>
            </div>

            {{-- Container Tabel --}}
            <div class="space-y-8">
                
                {{-- Tabel 1: Pesanan Menunggu Pembayaran --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <h2 class="font-bold mb-4 text-orange-600">Pesanan Menunggu Pembayaran</h2>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-sm border-b">
                                <th class="pb-3 w-1/4">Waktu</th>
                                <th class="pb-3 w-1/4">No. Order</th>
                                <th class="pb-3 w-1/4">Tipe</th>
                                <th class="pb-3 w-1/4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingOrders as $order)
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="py-4 text-sm">{{ $order->created_at->format('H:i') }}</td>
                                <td class="py-4 font-bold text-gray-800">
                                    TRX-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 text-sm">{{ $order->tipe_pesanan }}</td>
                                <td class="py-4 flex gap-2">
                                    <a href="{{ route('kasir.pos.input', $order->meja_id ?? null) }}" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-700">Edit</a>
                                    <a href="{{ route('kasir.pembayaran.detail', $order->id) }}" class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-700">Bayar</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-8 text-center text-gray-500 italic">Tidak ada pesanan pending.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tabel 2: Riwayat Pesanan Terakhir --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <h2 class="font-bold mb-4 text-gray-800">Riwayat Pesanan Terakhir</h2>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-sm border-b">
                                <th class="pb-3 w-1/4">Waktu</th>
                                <th class="pb-3 w-1/4">No. Order</th>
                                <th class="pb-3 w-1/4">Tipe</th>
                                <th class="pb-3 w-1/4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentHistory as $history)
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="py-4 text-sm">{{ $history->updated_at->format('H:i') }}</td>
                                <td class="py-4 font-bold text-gray-800">
                                    TRX-{{ str_pad($history->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 text-sm">{{ $history->tipe_pesanan }}</td>
                                <td class="py-4">
                                    <a href="{{ route('kasir.pembayaran.detail', $history->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-yellow-600 transition">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-8 text-center text-gray-500 italic">Belum ada riwayat transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>