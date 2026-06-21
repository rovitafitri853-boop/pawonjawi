<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $tanggal_mulai }} sd {{ $tanggal_selesai }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #000; padding: 30px; }
        
        /* Header Toko */
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 12px; }
        .divider { border-top: 2px solid #000; margin-top: 10px; }
        
        /* Judul Laporan */
        .title { text-align: center; margin-top: 20px; }
        .title h2 { font-size: 16px; margin-bottom: 5px; }
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { border: 1px solid #000; padding: 8px; background-color: #eee; text-align: center; }
        td { border: 1px solid #000; padding: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Footer/Tanda Tangan */
        .footer-space { margin-top: 50px; }
        .signature { float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $setting->nama_toko ?? 'NAMA TOKO ANDA' }}</h1>
        <p>{{ $setting->alamat ?? 'Jl. Contoh Alamat No. 123, Kota Anda' }}</p>
        <p>Telepon: {{ $setting->telepon ?? '0812-3456-7890' }}</p>
        <div class="divider"></div>
    </div>

    <div class="title">
        <h2>LAPORAN PENJUALAN HARIAN</h2>
        <p>Periode: {{ date('d-m-Y', strtotime($tanggal_mulai)) }} s/d {{ date('d-m-Y', strtotime($tanggal_selesai)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap_harian as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d-m-Y', strtotime($data->date)) }}</td>
                <td class="text-center">{{ $data->total_transaksi }}</td>
                <td class="text-right">Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL KESELURUHAN</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($rekap_harian->sum('total_pendapatan'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-space">
        <div class="signature">
            <p>{{ date('d F Y') }}</p>
            <p>Penanggung Jawab,</p>
            <br><br><br>
            <p>( ........................... )</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <script>
        // Jalankan print otomatis saat halaman selesai dimuat
        window.onload = function() {
            window.print();
        };

        // Otomatis kembali ke halaman laporan setelah dialog print ditutup
        window.onafterprint = function() {
            window.location.href = "{{ route('admin.laporan.index') }}";
        };
    </script>

</body>
</html>