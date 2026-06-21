<x-app-layout>
    {{-- Header dengan Judul Cetak Struk --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Struk') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-md mx-auto">
            
            {{-- Card Utama --}}
            <div class="bg-white p-6 rounded-3xl shadow-2xl border border-gray-100">
                
                {{-- Area Struk --}}
                <div id="print-area" class="w-full bg-gray-50 p-6 font-mono text-[13px] text-black border border-dashed border-gray-400 mb-6">
                    
                    {{-- Header Toko (MENGAMBIL DATA DARI SETTING) --}}
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-black uppercase tracking-widest text-gray-800">
                            {{ $setting->nama_toko ?? 'NAMA TOKO' }}
                        </h2>
                        <div class="h-px w-16 bg-black mx-auto my-2"></div>
                        <p class="text-[10px] text-gray-600">
                            {{ $setting->alamat ?? 'Alamat Toko Belum Diatur' }}
                        </p>
                    </div>

                    {{-- Info Transaksi --}}
                    <div class="border-t border-b border-black py-2 mb-3 text-[11px]">
                        <div class="flex justify-between">
                            <span>{{ $transaksi->updated_at->format('d/m/Y') }}</span>
                            <span>{{ $transaksi->updated_at->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>TRX: #{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ strtoupper($transaksi->tipe_pesanan) }}</span>
                        </div>
                    </div>

                    {{-- Daftar Item --}}
                    <div class="mb-3">
                        @foreach($transaksi->detail as $item)
                        <div class="flex justify-between items-start mb-1">
                            <span class="w-3/4">{{ $item->menu->nama_menu }} x{{ $item->jumlah }}</span>
                            <span class="w-1/4 text-right">{{ number_format($item->subtotal) }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total --}}
                    <div class="border-t border-dashed border-black pt-3 mb-3">
                        <div class="flex justify-between font-bold text-[14px]">
                            <span>TOTAL</span>
                            <span>Rp {{ number_format($transaksi->total_harga) }}</span>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="text-[11px] mb-4 space-y-1">
                        <div class="flex justify-between">
                            <span class="capitalize">{{ $transaksi->metode_pembayaran }}</span>
                            <span>Rp {{ number_format($transaksi->bayar) }}</span>
                        </div>
                        
                        @if($transaksi->metode_pembayaran !== 'tunai')
                        <div class="flex justify-between text-[10px] text-gray-600 italic">
                            <span>Ref: {{ $transaksi->referensi }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between font-bold border-t border-gray-300 pt-1">
                            <span>Kembali</span>
                            <span>Rp {{ number_format($transaksi->bayar - $transaksi->total_harga) }}</span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center border-t border-black pt-3">
                        <p class="font-bold text-[11px]">TERIMA KASIH</p>
                    </div>
                </div>

                {{-- Tombol (Hilang saat diprint) --}}
                <div class="grid grid-cols-2 gap-3 no-print">
                    <a href="{{ route('kasir.riwayat') }}" class="text-center bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg">
                        Kembali
                    </a>
                    <button onclick="window.print()" class="bg-black text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg">
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS & Script --}}
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #print-area, #print-area * { visibility: visible !important; }
            
            @page { 
                size: 58mm auto; 
                margin: 0; 
            }

            body { margin: 0; padding: 0; }
            #print-area { 
                position: absolute; 
                left: 0; 
                top: 0; 
                width: 58mm !important; 
                padding: 5px !important;
                border: none !important;
                box-shadow: none !important;
                background: white !important;
            }
        }
    </style>

    <script>
        window.onload = function() {
            @if(session('print'))
                setTimeout(function() { window.print(); }, 500);
            @endif
        };
    </script>
</x-app-layout>