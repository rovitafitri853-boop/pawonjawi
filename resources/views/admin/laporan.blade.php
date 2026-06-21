<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    {{-- CSS Khusus untuk Print --}}
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 20px; }
            .shadow, .bg-white, .dark\:bg-gray-800 { box-shadow: none !important; background: white !important; border: none !important; }
            .print-header { display: block !important; text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
            table { width: 100% !important; border-collapse: collapse !important; margin-top: 10px !important; }
            th { background-color: #f3f4f6 !important; border: 1px solid #333 !important; padding: 8px !important; color: black !important; }
            td { border: 1px solid #ccc !important; padding: 8px !important; }
            .grid { display: block !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="print-header" style="display:none;">
                <h1 style="font-size: 24px; font-weight: bold;">LAPORAN PENJUALAN</h1>
                <p>Periode: {{ $tanggal_mulai }} sampai {{ $tanggal_selesai }}</p>
            </div>

            {{-- Filter Tanggal & Tombol Aksi --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6 no-print">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-end gap-4">
                    
                    {{-- Grup Tanggal --}}
                    <div class="flex gap-4">
                        <div>
                            <label class="block text-sm dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ $tanggal_mulai }}" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white h-[42px]">
                        </div>
                        <div>
                            <label class="block text-sm dark:text-gray-300 mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ $tanggal_selesai }}" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white h-[42px]">
                        </div>
                    </div>

                    {{-- Tombol Tampilkan --}}
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 text-sm h-[42px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                        Tampilkan
                    </button>

                    {{-- Spacer --}}
                    <div class="flex-grow"></div>

                    {{-- Grup Tombol Kanan --}}
                    <div class="flex gap-2">
                        <a href="{{ route('admin.laporan.cetak', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai]) }}" 
                           target="_blank" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 text-sm h-[42px]">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
                           Cetak
                        </a>

                        {{-- Tombol PDF dengan parameter unik &rand=time() agar selalu ter-update --}}
                        <a href="{{ route('admin.laporan.download', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai]) . '&rand=' . time() }}" 
                           class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 text-sm h-[42px]">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                           Download PDF
                        </a>
                    </div>
                </form>
            </div>

            {{-- Ringkasan Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-500">Total Transaksi</p>
                    <h3 class="text-2xl font-bold dark:text-white">{{ $total_transaksi }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-500">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold dark:text-white">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-500">Total Menu Terjual</p>
                    <h3 class="text-2xl font-bold dark:text-white">{{ $total_menu_terjual }} Pcs</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-blue-600">
                    <p class="text-gray-500">Menu Terlaris</p>
                    <h3 class="text-lg font-bold dark:text-white mt-1">{{ $menus_terlaris->first()->menu->nama_menu ?? 'N/A' }}</h3>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mt-2">{{ $menus_terlaris->first()->total_qty ?? 0 }} Terjual</p>
                </div>
            </div>

            {{-- Grafik & Tabel Menu Terlaris --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Grafik Pendapatan</h3>
                    <canvas id="pendapatanChart" height="150"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Daftar Menu Terlaris (Top 5)</h3>
                    <table class="w-full text-left">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr><th class="pb-2 w-16">No</th><th class="pb-2">Menu</th><th class="pb-2">Total Terjual</th></tr>
                        </thead>
                        <tbody class="dark:text-gray-300">
                            @forelse($menus_terlaris as $item)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="py-3">{{ $loop->iteration }}</td>
                                <td class="py-3">{{ $item->menu->nama_menu ?? 'Menu Dihapus' }}</td>
                                <td class="py-3">{{ $item->total_qty }} Pcs</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4">Data tidak ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rekap Penjualan Harian --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="font-bold mb-4 dark:text-white">Detail Penjualan Per Hari</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-gray-500 dark:text-gray-400 border-b">
                            <tr><th class="pb-3 w-16">No</th><th class="pb-3">Tanggal</th><th class="pb-3">Jumlah Transaksi</th><th class="pb-3">Total Pendapatan</th></tr>
                        </thead>
                        <tbody class="dark:text-gray-300">
                            @foreach($rekap_harian as $index => $data)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3">{{ $index + 1 }}</td>
                                <td class="py-3">{{ $data->date }}</td>
                                <td class="py-3">{{ $data->total_transaksi }} Transaksi</td>
                                <td class="py-3 font-semibold text-blue-600 dark:text-blue-400">Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('pendapatanChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($rekap_harian->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($rekap_harian->pluck('total_pendapatan')) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    fill: true
                }]
            },
            options: { responsive: true }
        });
    </script>
</x-app-layout>