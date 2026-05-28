<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 1cm; }
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
        .availability { text-align: center; margin: 10px 0; padding: 10px; border: 2px solid #16a34a; border-radius: 8px; }
        .availability h1 { font-size: 32px; margin: 0; color: #16a34a; }
        .availability p { margin: 2px 0; color: #666; font-size: 9px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .table th, .table td { border: 1px solid #999; padding: 3px; text-align: center; font-size: 8px; }
        .table th { background: #e5e7eb; }
        .stat-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .stat-table th, .stat-table td { border: 1px solid #ccc; padding: 4px; text-align: center; font-size: 8px; }
        .stat-table th { background: #f3f4f6; }
        .safe-row { background: #f0fdf4; }
        .incident-row { background: #fef2f2; }
        .footer { margin-top: 15px; float: right; width: 200px; text-align: center; font-size: 8px; }
        .footer hr { width: 140px; margin: 4px auto; }
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
        </table>
    </div>

    <div class="availability">
        <p>Tingkat Ketersediaan Infrastruktur</p>
        <h1>{{ $availabilityPercent }}%</h1>
        <p>Sistem beroperasi normal {{ $hariAman }} dari {{ $totalHari }} hari pemantauan</p>
    </div>

    <table class="stat-table">
        <tr>
            <th>Metrik</th>
            <th>Nilai</th>
        </tr>
        <tr class="safe-row">
            <td><strong>Total Masa Pemantauan</strong></td>
            <td>{{ $totalHari }} hari</td>
        </tr>
        <tr class="safe-row">
            <td><strong>Hari Operasi Normal (Aman)</strong></td>
            <td>{{ $hariAman }} hari</td>
        </tr>
        <tr class="incident-row">
            <td><strong>Hari Terjadi Insiden Keamanan</strong></td>
            <td>{{ $hariTerkenaSerangan }} hari</td>
        </tr>
        <tr class="incident-row">
            <td><strong>Total Insiden Keamanan</strong></td>
            <td>{{ $totalSerangan }} insiden</td>
        </tr>
        <tr>
            <td><strong>Insiden Berhasil Diblokir</strong></td>
            <td>{{ $totalBlocked }} insiden</td>
        </tr>
        <tr>
            <td><strong>Rata-rata Serangan per Hari</strong></td>
            <td>{{ $avgPerHari }} insiden/hari</td>
        </tr>
    </table>

    <p style="text-align:center; margin-top:10px; font-size:7px; color:#666;">
        <em>Laporan ini menyajikan statistik durasi operasional normal jaringan dibandingkan dengan waktu terjadinya insiden keamanan sebagai bahan evaluasi performa sistem.</em>
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
