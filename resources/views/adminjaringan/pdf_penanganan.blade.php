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
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .table th, .table td { border: 1px solid #999; padding: 3px; text-align: center; font-size: 7px; }
        .table th { background: #e5e7eb; }
        .sumber-otomatis { color: #0891b2; font-weight: bold; }
        .sumber-manual { color: #7c3aed; font-weight: bold; }
        .tindakan-block { color: #dc2626; font-weight: bold; }
        .tindakan-whitelist { color: #16a34a; font-weight: bold; }
        .tindakan-drop { color: #ea580c; font-weight: bold; }
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
            <tr>
                <td><strong>Total Log Otomatis:</strong></td>
                <td>{{ $semuaLog->where('jenis_log', 'Otomatis')->count() }}</td>
                <td><strong>Total Intervensi Manual:</strong></td>
                <td>{{ $semuaLog->where('jenis_log', 'Manual')->count() }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu Kejadian</th>
                <th>IP Address</th>
                <th>Jenis Intrusi</th>
                <th>Risk Level</th>
                <th>Tindakan Sistem</th>
                <th>Tindakan Admin</th>
                <th>Sumber</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semuaLog as $i => $log)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($log['waktu'])->format('d/m/Y H:i') }}</td>
                <td>{{ $log['ip_address'] }}</td>
                <td>{{ $log['jenis_intrusi'] }}</td>
                <td>{{ $log['risk_level'] }}</td>
                <td>{{ $log['tindakan_sistem'] }}</td>
                <td class="
                    @if($log['tindakan_admin'] == 'BLOCK') tindakan-block
                    @elseif($log['tindakan_admin'] == 'WHITELIST') tindakan-whitelist
                    @elseif($log['tindakan_admin'] == 'DROP') tindakan-drop
                    @endif
                ">{{ $log['tindakan_admin'] }}</td>
                <td class="{{ $log['jenis_log'] == 'Manual' ? 'sumber-manual' : 'sumber-otomatis' }}">{{ $log['jenis_log'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align:center; margin-top:10px; font-size:7px; color:#666;">
        <em>Laporan ini mencatat detail waktu kejadian, jenis intrusi, dan tindakan mitigasi yang dilakukan oleh administrator jaringan sebagai dokumentasi teknis penanganan insiden.</em>
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
