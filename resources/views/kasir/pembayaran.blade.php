<x-app-layout>
    {{-- Header dengan Judul Pembayaran --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    {{-- Kolom Kiri: Ringkasan --}}
                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <h2 class="text-sm font-bold text-gray-500 uppercase mb-4">Ringkasan Pesanan</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">No. Order</span>
                                <span class="font-bold">TRX-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-4 space-y-2">
                            @foreach($transaksi->detail as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ $item->jumlah }}x {{ $item->menu->nama_menu }}</span>
                                    <span>Rp {{ number_format($item->subtotal) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 border-t pt-4 flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span class="text-gray-800">Rp {{ number_format($transaksi->total_harga ?? 0) }}</span>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Form Pembayaran --}}
                    <div class="p-6 bg-gray-50">
                        <form action="{{ route('kasir.proses.bayar', $transaksi->id) }}" method="POST" id="formPembayaran" onsubmit="return confirmPembayaran(event)">
                            @csrf
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="metode" id="metode" class="w-full border-gray-300 rounded-md shadow-sm" onchange="toggleFields()">
                                    <option value="tunai" {{ old('metode', $transaksi->metode_pembayaran ?? 'tunai') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="qris" {{ old('metode', $transaksi->metode_pembayaran ?? '') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="kartu" {{ old('metode', $transaksi->metode_pembayaran ?? '') == 'kartu' ? 'selected' : '' }}>Kartu Debit/Transfer</option>
                                </select>
                            </div>

                            <div id="field-tunai" class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Uang Diterima</label>
                                <input type="number" name="diterima" id="diterima" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="0" oninput="hitungKembalian()" required step="any">
                                <div class="mt-3 text-sm">
                                    <span>Kembalian:</span>
                                    <span id="kembalian" class="font-bold text-blue-600">Rp 0</span>
                                </div>
                            </div>

                            <div id="field-referensi" class="mb-5 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Referensi / Bukti</label>
                                <input type="text" name="referensi" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan kode referensi">
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('kasir.riwayat') }}" class="flex-1 text-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition shadow">Kembali</a>
                                <button type="submit" id="btnProses" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow transition">Proses Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });

        // Fungsi Konfirmasi SweetAlert
        function confirmPembayaran(event) {
            event.preventDefault(); 
            
            const metode = document.getElementById('metode').value;
            const total = {{ $transaksi->total_harga ?? 0 }};
            const diterima = parseFloat(document.getElementById('diterima').value) || 0;

            // Validasi jika uang kurang (khusus tunai)
            if (metode === 'tunai' && diterima < total) {
                Swal.fire({
                    icon: 'error',
                    title: 'Uang Kurang!',
                    text: 'Jumlah uang diterima tidak mencukupi untuk membayar pesanan.'
                });
                return false;
            }

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Pastikan data pembayaran sudah benar. Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Bayar Sekarang!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('btnProses');
                    btn.disabled = true;
                    btn.innerText = 'Memproses...';
                    document.getElementById('formPembayaran').submit();
                }
            });
        }

        function toggleFields() {
            const metode = document.getElementById('metode').value;
            const tunaiDiv = document.getElementById('field-tunai');
            const refDiv = document.getElementById('field-referensi');
            const inputDiterima = document.getElementById('diterima');
            
            if (metode === 'tunai') {
                tunaiDiv.classList.remove('hidden');
                refDiv.classList.add('hidden');
                inputDiterima.required = true;
            } else {
                tunaiDiv.classList.add('hidden');
                refDiv.classList.remove('hidden');
                inputDiterima.required = false;
            }
        }

        function hitungKembalian() {
            const total = {{ $transaksi->total_harga ?? 0 }};
            const diterima = parseFloat(document.getElementById('diterima').value) || 0;
            const kembalian = diterima - total;
            const display = document.getElementById('kembalian');
            
            if (diterima > 0) {
                display.innerText = kembalian >= 0 ? 'Rp ' + kembalian.toLocaleString('id-ID') : 'Uang Kurang';
                display.className = kembalian >= 0 ? 'font-bold text-blue-600' : 'font-bold text-red-600';
            } else {
                display.innerText = 'Rp 0';
            }
        }
    </script>
</x-app-layout>