<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .kop { border-bottom: 3px solid #1a56db; padding-bottom: 8px; text-align: center; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 65px; }
        .header-text { margin-left: 75px; margin-right: 75px; }
        .header-text h2 { margin:0; text-transform: uppercase; font-size: 13px; }
        .header-text h3 { margin:4px 0; text-transform: uppercase; color: #1a56db; font-size: 11px; }
        .header-text p { margin:0; font-size:7px; color: #666; }
        .title { text-align:center; text-decoration: underline; margin-top: 12px; font-size: 12px; }
        .info-proses { margin-top: 8px; font-size: 7px; color: #666; border: 1px dashed #ccc; padding: 5px; }
        .info-proses td { padding: 1px 4px; }
        .stat-card { margin-top: 8px; }
        .stat-card table { width: 100%; border-collapse: collapse; }
        .stat-card th, .stat-card td { border: 1px solid #ccc; padding: 3px; text-align: center; font-size: 8px; }
        .stat-card th { background: #e5e7eb; }
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .table th, .table td { border: 1px solid #999; padding: 3px; text-align: center; font-size: 8px; }
        .table th { background: #e5e7eb; }
        .footer { margin-top: 15px; float: right; width: 200px; text-align: center; font-size: 8px; }
        .footer hr { width: 140px; margin: 4px auto; }
        .dominant { background: #fef2f2; font-weight: bold; }
        .badge-critical { color: #dc2626; font-weight: bold; }
        .badge-high { color: #ea580c; font-weight: bold; }
        .badge-medium { color: #ca8a04; }
        .badge-low { color: #16a34a; }
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

    <div class="info-proses">
        <table>
            <tr>
                <td width="20%"><strong>Dicetak oleh:</strong></td>
                <td width="30%">{{ $admin }}</td>
                <td width="20%"><strong>Tanggal Cetak:</strong></td>
                <td width="30%">{{ date('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Total Insiden:</strong></td>
                <td>{{ $totalSerangan }} kejadian</td>
                <td><strong>Ancaman Dominan:</strong></td>
                <td>{{ $dominant->kategori ?? '-' }} ({{ $dominant->persentase ?? 0 }}%)</td>
            </tr>
        </table>
    </div>

    <div class="stat-card">
        <strong style="font-size:9px;">Ringkasan Distribusi Kategori Ancaman:</strong>
        <table>
            <tr>
                <th>Kategori Ancaman</th>
                <th>Jumlah Insiden</th>
                <th>Persentase</th>
                <th>Tingkat Dominasi</th>
            </tr>
            @foreach($statKategori as $item)
            <tr @if($loop->first) class="dominant" @endif>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->persentase }}%</td>
                <td>{{ str_repeat('█', ceil($item->persentase / 10)) }}</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold; background:#f0fdf4;">
                <td>TOTAL</td>
                <td>{{ $totalSerangan }}</td>
                <td>100%</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="stat-card">
        <strong style="font-size:9px;">Distribusi Tingkat Keparahan (Risk Level):</strong>
        <table>
            <tr>
                <th>Risk Level</th>
                <th>Jumlah</th>
            </tr>
            @foreach($trendRisk as $item)
            <tr>
                <td class="
                    @if($item->risk_level == 'Critical') badge-critical
                    @elseif($item->risk_level == 'High') badge-high
                    @elseif($item->risk_level == 'Medium') badge-medium
                    @else badge-low @endif
                ">{{ $item->risk_level }}</td>
                <td>{{ $item->total }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <p style="text-align:center; margin-top:10px; font-size:8px; color:#666;">
        <em>Laporan ini merangkum frekuensi insiden keamanan berdasarkan kategori aturan yang terdeteksi untuk memetakan jenis ancaman paling dominan di lingkungan jaringan RSUD Ansari Saleh.</em>
    </p>

    <div class="footer">
        <p>Banjarmasin, {{ date('d F Y') }}</p>
        <p>Admin IT Security,</p>
        <div style="height:40px"></div>
        <hr>
        <strong>( {{ $admin }} )</strong>
        <p style="font-size:6px; color:#999; margin-top:3px;">Dokumen ini dicetak otomatis dari sistem IDS RSUD Ansari Saleh</p>
    </div>
</body>
</html>
