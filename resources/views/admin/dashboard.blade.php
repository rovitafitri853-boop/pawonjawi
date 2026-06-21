<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <a href="{{ route('admin.laporan.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-all duration-200 border border-transparent hover:border-blue-500">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-bold dark:text-white mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </a>
                
                <a href="{{ route('admin.laporan.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-all duration-200 border border-transparent hover:border-blue-500">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold dark:text-white mt-1">{{ $totalTransaksi }}</h3>
                </a>

                <a href="{{ route('admin.menu.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-all duration-200 border border-transparent hover:border-blue-500">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Menu</p>
                    <h3 class="text-2xl font-bold dark:text-white mt-1">{{ $jumlahMenu }}</h3>
                </a>

                <a href="{{ route('admin.meja.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-all duration-200 border border-transparent hover:border-blue-500">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Meja</p>
                    <h3 class="text-2xl font-bold dark:text-white mt-1">{{ $jumlahMeja }}</h3>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Grafik Pendapatan (7 Hari Terakhir)</h3>
                    <canvas id="dashboardChart" height="100"></canvas>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Menu Terlaris Hari Ini</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm dark:text-gray-300">
                            <thead>
                                <tr class="border-b dark:border-gray-700 text-gray-500 uppercase tracking-wider text-xs">
                                    <th class="pb-3 w-10 text-center">No</th>
                                    <th class="pb-3">Menu</th>
                                    <th class="pb-3 text-right">Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($menus_terlaris as $item)
                                <tr>
                                    <td class="py-3 text-center text-gray-500 font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-3 font-medium text-gray-800 dark:text-gray-200 truncate max-w-[120px]">{{ $item->menu->nama_menu ?? 'Menu Dihapus' }}</td>
                                    <td class="py-3 text-right font-bold text-blue-600 dark:text-blue-400">{{ $item->total_qty }} <span class="text-gray-400 font-normal">pcs</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400 italic">Belum ada data transaksi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($rekap_mingguan->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($rekap_mingguan->pluck('total_pendapatan')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    </script>
</x-app-layout>