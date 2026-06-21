<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 flex justify-between items-center">
                    <form method="GET" action="{{ route('kasir.riwayat') }}" class="flex items-center space-x-2">
                        <input type="number" name="search" value="{{ request('search') }}" 
                            placeholder="Cari No. Order..." class="border-gray-300 rounded-md shadow-sm text-sm w-48">
                        <button type="submit" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700" title="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <a href="{{ route('kasir.riwayat') }}" class="bg-gray-200 text-gray-700 p-2 rounded-md hover:bg-gray-300" title="Reset">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </form>

                    <form method="GET" action="{{ route('kasir.riwayat') }}" class="flex items-center space-x-2">
                        <label class="text-sm font-bold">Status:</label>
                        <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </form>
                </div>

                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr class="text-gray-600">
                            <th class="py-3">No. Order</th>
                            <th class="py-3">Tipe</th>
                            <th class="py-3">Meja</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($transaksis as $t)
                        <tr>
                            <td class="py-3 font-bold text-blue-600">TRX-{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 capitalize">{{ $t->tipe_pesanan }}</td>
                            <td class="py-3">{{ $t->meja ? $t->meja->nomor_meja : '-' }}</td>
                            <td class="py-3">Rp {{ number_format($t->total_harga) }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $t->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                    {{ strtoupper($t->status) }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($t->status == 'pending')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('kasir.pos.edit', $t->meja_id ?? '0') }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Edit</a>
                                        <a href="{{ route('kasir.pembayaran.detail', $t->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Bayar</a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>