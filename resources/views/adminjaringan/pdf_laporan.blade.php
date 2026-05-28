<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .kop { border-bottom: 3px solid #1a56db; padding-bottom: 10px; text-align: center; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 70px; }
        .header-text { margin-left: 80px; margin-right: 80px; }
        .header-text h2 { margin:0; text-transform: uppercase; font-size: 14px; }
        .header-text h3 { margin:5px 0; text-transform: uppercase; color: #1a56db; font-size: 12px; }
        .header-text p { margin:0; font-size:8px; color: #666; }
        .title { text-align:center; text-decoration: underline; margin-top: 15px; font-size: 13px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 8px; }
        .table th, .table td { border: 1px solid #999; padding: 4px; text-align: center; }
        .table th { background: #e5e7eb; font-size: 8px; }
        .table td { font-size: 8px; }
        .info-proses { margin-top: 10px; font-size: 8px; color: #666; border: 1px dashed #ccc; padding: 6px; }
        .info-proses table { width: 100%; }
        .info-proses td { padding: 2px 5px; }
        .ringkasan { margin-top: 10px; }
        .ringkasan table { width: 100%; border-collapse: collapse; }
        .ringkasan th, .ringkasan td { border: 1px solid #ccc; padding: 4px; text-align: center; font-size: 8px; }
        .ringkasan th { background: #f3f4f6; }
        .footer { margin-top: 20px; float: right; width: 200px; text-align: center; font-size: 9px; }
        .footer hr { width: 150px; margin: 5px auto; }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div class="header-text">
            <h2>Pemerintah Provinsi Kalimantan Selatan</h2>
            <h3>RSUD Dr. H. Moch. Ansari Saleh</h3>
            <p>Jl. Brigjend H. Hasan Basry No.1, Banjarmasin, Kalimantan Selatan</p>
            <p>Telepon: (0511) 3300741 | Email: rsudansarisaleh@kalselprov.go.id</p>
        </div>
    </div>

    <h3 class="title">{{ $title }}</h3>

    <!-- INFORMASI PROSES CETAK -->
    <div class="info-proses">
        <table>
            <tr>
                <td width="20%"><strong>Dicetak oleh:</strong></td>
                <td width="30%">{{ $admin }}</td>
                <td width="20%"><strong>Tanggal Cetak:</strong></td>
                <td width="30%">{{ date('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Jenis Laporan:</strong></td>
                <td>{{ $title }}</td>
                <td><strong>Periode Data:</strong></td>
                <td>Seluruh data tersimpan</td>
            </tr>
            <tr>
                <td><strong>Jumlah Data:</strong></td>
                <td>{{ $totalData ?? count($logs) }} entri</td>
                <td><strong>Sistem:</strong></td>
                <td>IDS RSUD Ansari Saleh</td>
            </tr>
        </table>
    </div>

    <!-- RINGKASAN STATISTIK -->
    @if(isset($totalKategori) && $totalKategori->count() > 0)
    <div class="ringkasan">
        <strong style="font-size:9px;">Ringkasan Kategori:</strong>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
            @foreach($totalKategori as $kategori => $jumlah)
            <tr>
                <td>{{ $kategori }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Source IP</th>
                <th>Origin</th>
                <th>Kategori</th>
                <th>Target Endpoint</th>
                <th>Severity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $i => $l)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $l->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $l->ip_address }}</td>
                <td>{{ $l->origin ?? 'Local' }}</td>
                <td>{{ $l->kategori }}</td>
                <td>{{ $l->endpoint }}</td>
                <td>{{ strtoupper($l->risk_level) }}</td>
                <td>{{ $l->action_taken }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Banjarmasin, {{ date('d F Y') }}</p>
        <p>Admin IT Security,</p>
        <div style="height:50px"></div>
        <hr>
        <strong>( {{ $admin }} )</strong>
        <p style="font-size:7px; color:#999; margin-top:5px;">Dokumen ini dicetak secara otomatis dari sistem IDS</p>
    </div>
</body>
</html>
