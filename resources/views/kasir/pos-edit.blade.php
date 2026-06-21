<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pesanan') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="h-screen flex flex-col bg-gray-100 py-6" x-data="{ activeCategory: 'Semua', search: '' }">
        
        <main class="flex-1 flex gap-6 px-6 overflow-hidden">
            {{-- KOLOM KIRI: PESANAN --}}
            <div class="w-1/3 bg-white rounded-2xl shadow-sm border p-5 flex flex-col">
                <h3 class="font-bold text-lg mb-4 text-gray-900 border-b pb-2">
                    Edit Pesanan {{ 'TRX-' . $transaksi->created_at->format('ymd') . '-' . str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                </h3>
                
                {{-- Detail Meja/Tipe --}}
                <div class="mb-4 grid grid-cols-2 gap-2">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <label class="text-[9px] text-gray-400 uppercase font-bold">Meja</label>
                        <p class="text-xs font-bold text-gray-700">{{ $transaksi->meja->nomor_meja ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <label class="text-[9px] text-gray-400 uppercase font-bold">Tipe</label>
                        <p class="text-xs font-bold text-gray-700 capitalize">{{ str_replace('_', '-', $transaksi->tipe ?? 'dine-in') }}</p>
                    </div>
                </div>

                {{-- LIST ITEM PESANAN --}}
                <div class="flex-1 overflow-y-auto border-t pt-3">
                    @forelse($transaksi->detail as $item)
                    <div class="grid grid-cols-12 gap-1 items-center border-b pb-2 mb-2 text-[11px]">
                        <div class="col-span-4 font-semibold text-gray-700 truncate">{{ $item->menu->nama_menu }}</div>
                        <div class="col-span-2 text-center text-gray-500">{{ number_format($item->menu->harga) }}</div>
                        <div class="col-span-2 flex justify-center items-center gap-1">
                            <button type="button" onclick="updateQty('{{ route('kasir.updateItem', $item->id) }}', 'kurang')" class="px-1.5 border rounded hover:bg-gray-100">-</button>
                            <span class="font-bold w-4 text-center">{{ $item->jumlah }}</span>
                            <button type="button" onclick="updateQty('{{ route('kasir.updateItem', $item->id) }}', 'tambah')" class="px-1.5 border rounded hover:bg-gray-100">+</button>
                        </div>
                        <div class="col-span-3 text-right font-bold">{{ number_format($item->subtotal) }}</div>
                        <div class="col-span-1 flex justify-center">
                            <button type="button" onclick="deleteItem('{{ route('kasir.destroyItem', $item->id) }}')" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center py-10 italic text-sm">Pesanan kosong</p>
                    @endforelse
                </div>

                {{-- FORM SIMPAN & BATAL --}}
                <form action="{{ route('kasir.transaksi.update', $transaksi->id) }}" method="POST" id="formSimpan">
                    @csrf
                    @method('PUT')
                    <div class="border-t pt-4 mt-2">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-bold text-sm">Total</span>
                            <span class="font-bold text-lg text-blue-600">Rp {{ number_format($transaksi->total_harga) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('kasir.riwayat') }}" class="text-center bg-red-600 text-white py-2 rounded-lg font-bold text-[10px] hover:bg-red-700 transition">Batal</a>
                            <button type="button" onclick="confirmSave()" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold text-[10px] hover:bg-blue-700 transition">Simpan Pesanan</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: MENU --}}
            <div class="flex-1 bg-white rounded-2xl shadow-sm border p-5 overflow-y-auto">
                <input x-model="search" type="text" placeholder="Cari menu..." class="w-full p-4 rounded-xl border-gray-200 mb-4 shadow-sm">
                
                <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                    <button @click="activeCategory = 'Semua'" :class="activeCategory === 'Semua' ? 'bg-blue-600 text-white' : 'bg-white'" class="px-4 py-1.5 rounded-full text-xs font-bold border transition">Semua</button>
                    @foreach($kategoris as $kat)
                        <button @click="activeCategory = '{{ $kat->nama_kategori }}'" :class="activeCategory === '{{ $kat->nama_kategori }}' ? 'bg-blue-600 text-white' : 'bg-white'" class="px-4 py-1.5 rounded-full text-xs font-bold border transition">{{ $kat->nama_kategori }}</button>
                    @endforeach
                </div>

                <div class="grid grid-cols-4 gap-4">
                    @foreach($kategoris as $kat)
                        @foreach($kat->menus as $menu)
                        <div class="bg-white p-3 rounded-xl border hover:shadow-md transition" x-show="(activeCategory == 'Semua' || activeCategory == '{{ $kat->nama_kategori }}') && '{{ strtolower($menu->nama_menu) }}'.includes(search.toLowerCase())">
                            <p class="font-bold text-sm truncate">{{ $menu->nama_menu }}</p>
                            <p class="text-gray-500 font-bold text-xs mb-3">Rp {{ number_format($menu->harga) }}</p>
                            <button type="button" onclick="addToOrder('{{ route('kasir.transaksi.store') }}', {{ $menu->id }}, {{ $menu->harga }})" class="w-full bg-gray-900 text-white py-1.5 rounded-lg text-xs font-bold hover:bg-black">+ Tambah</button>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <script>
        function deleteItem(url) {
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ _method: 'DELETE' }) }).then(() => window.location.reload());
        }

        function updateQty(url, action) {
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ action }) }).then(() => window.location.reload());
        }

        function addToOrder(url, menu_id, harga) {
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ menu_id, harga, jumlah: 1, transaksi_id: {{ $transaksi->id }} }) }).then(() => window.location.reload());
        }

        function confirmSave() {
            Swal.fire({
                title: 'Simpan Perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formSimpan').submit();
                }
            });
        }
    </script>
</x-app-layout>