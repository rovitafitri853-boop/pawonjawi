<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Pesanan') }}
        </h2>
    </x-slot>

    <div class="h-screen flex flex-col bg-gray-100 py-6" x-data="{ activeCategory: 'Semua', search: '', tipe: 'dine_in' }">
        
        <main class="flex-1 flex gap-6 px-6 overflow-hidden">
            {{-- KOLOM KIRI: PESANAN --}}
            <div class="w-1/3 bg-white rounded-2xl shadow-sm border p-5 flex flex-col">
                <h3 class="font-bold text-lg mb-4 text-gray-900 border-b pb-2">Pesanan Saat Ini</h3>
                
                <div class="mb-4 grid grid-cols-3 gap-2">
                    <div>
                        <label class="text-[9px] text-gray-400 uppercase font-bold">No. Order</label>
                        <div class="bg-blue-50 p-2 rounded text-[11px] font-bold text-blue-700 truncate text-center">
                            {{ isset($transaksi) ? 'TRX-' . now()->format('ymd') . '-' . str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) : 'TRX-0000' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] text-gray-400 uppercase font-bold">Meja</label>
                        <select id="selectMeja" name="meja_id" :disabled="tipe === 'take_away'" 
                                    onchange="window.location.href='/kasir/pos/input/'+this.value" 
                                    class="w-full p-2 border rounded text-[11px] font-bold">
                            <option value="">-</option> 
                            @foreach(\App\Models\Meja::all() as $m)
                                @php
                                    $isTerpakai = in_array($m->id, $mejaTerpakai ?? []);
                                    $isMejaAktif = (isset($meja) && $meja->id == $m->id);
                                @endphp
                                <option value="{{ $m->id }}" 
                                    {{ $isMejaAktif ? 'selected' : '' }}
                                    {{ $isTerpakai && !$isMejaAktif ? 'disabled' : '' }}>
                                    {{ $m->nomor_meja }} {{ $isTerpakai && !$isMejaAktif ? '(Terpakai)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] text-gray-400 uppercase font-bold">Tipe</label>
                        <select x-model="tipe" class="w-full p-2 border rounded text-[11px] font-bold">
                            <option value="dine_in">Dine In</option>
                            <option value="take_away">Take Away</option>
                        </select>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto border-t pt-3">
                    @forelse(($transaksi->detail ?? []) as $item)
                    <div class="grid grid-cols-12 gap-1 items-center border-b pb-2 mb-2 text-[11px]">
                        <div class="col-span-4 font-semibold text-gray-700 truncate">{{ $item->menu->nama_menu }}</div>
                        <div class="col-span-2 text-center text-gray-500">{{ number_format($item->menu->harga) }}</div>
                        <div class="col-span-3 flex justify-center items-center">
                            <button onclick="updateQty('{{ route('kasir.updateItem', $item->id) }}', 'kurang')" class="px-1.5 border rounded hover:bg-gray-100">-</button>
                            <span class="px-2 font-bold">{{ $item->jumlah }}</span>
                            <button onclick="updateQty('{{ route('kasir.updateItem', $item->id) }}', 'tambah')" class="px-1.5 border rounded hover:bg-gray-100">+</button>
                        </div>
                        <div class="col-span-3 text-right font-bold flex justify-end items-center gap-2">
                            {{ number_format($item->subtotal) }}
                            <button onclick="deleteItem('{{ route('kasir.destroyItem', $item->id) }}')" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center py-10 italic text-sm">Belum ada pesanan</p>
                    @endforelse
                </div>

                @if(isset($transaksi) && ($transaksi->detail->count() > 0))
                <div class="border-t pt-4 mt-2">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-sm">Total</span>
                        <span class="font-bold text-lg text-blue-600">Rp {{ number_format($transaksi->detail->sum('subtotal')) }}</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('kasir.riwayat') }}" class="bg-yellow-500 text-white py-2 rounded-lg font-bold text-[10px] text-center hover:bg-yellow-600 transition flex items-center justify-center">Batal</a>
                        <button type="button" onclick="confirmHapusPermanen('{{ route('kasir.batal', $transaksi->id) }}')" class="bg-red-500 text-white py-2 rounded-lg font-bold text-[10px] hover:bg-red-600 transition">Hapus</button>
                        <form id="formSimpan" action="{{ route('kasir.simpanPending') }}" method="POST" onsubmit="return validateSimpan(event)">
                            @csrf
                            <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold text-[10px] hover:bg-blue-700 transition">Simpan</button>
                        </form>
                    </div>
                </div>
                @endif
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
                            <button type="button" 
        onclick="tambahPesanan(tipe, {{ $menu->id }}, {{ $menu->harga }}, {{ isset($transaksi) ? $transaksi->id : 'null' }})" 
        class="w-full bg-gray-900 text-white py-1.5 rounded-lg text-xs font-bold hover:bg-black">
    + Tambah
</button>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <script>
        function validateSimpan(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: "Apakah Anda yakin ingin menyimpan pesanan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('formSimpan').submit(); }
            });
            return false;
        }

        function confirmHapusPermanen(url) {
            Swal.fire({
                title: 'Hapus Pesanan?',
                text: "Data akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = url; }
            });
        }

        function deleteItem(url) {
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ _method: 'DELETE' }) }).then(() => window.location.reload());
        }

        function updateQty(url, action) {
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ action }) }).then(() => window.location.reload());
        }

        function tambahPesanan(tipe, menu_id, harga, transaksi_id) {
    // Ambil meja_id dari selectMeja
    let selectMeja = document.getElementById('selectMeja');
    let meja_id = (tipe === 'take_away') ? null : (selectMeja ? selectMeja.value : null);

    let dataKirim = {
        tipe_pesanan: tipe, // Mengambil langsung dari Alpine.js
        meja_id: meja_id,
        menu_id: menu_id,
        harga: harga,
        transaksi_id: transaksi_id
    };

    fetch("{{ route('kasir.transaksi.store') }}", {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify(dataKirim)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload(); 
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan koneksi.');
    });

}
    </script>
</x-app-layout>