<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 1cm; }
        body { font-family: sans-serif; font-size: 10px; }
        
        /* CSS untuk Kop Surat */
        .kop { 
            border-bottom: 3px solid #000; 
            padding-bottom: 10px; 
            position: relative; /* Penting agar logo bisa diposisikan absolut */
            text-align: center;
        }
        
        .logo { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 70px; /* Sesuaikan ukuran logo kamu */
        }
        
        .header-text {
            margin-left: 80px; /* Beri jarak agar tidak tertutup logo */
            margin-right: 80px;
        }

        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 5px; text-align: center; }
        .table th { background: #eee; }
        .footer { margin-top: 30px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        
        <div class="header-text">
            <h2 style="margin:0; text-transform: uppercase;">Pemerintah Provinsi Kalimantan Selatan</h2>
            <h3 style="margin:5px 0; text-transform: uppercase; color: #000;">RSUD Dr. H. Moch. Ansari Saleh</h3>
            <p style="margin:0; font-size:9px">Jl. Brigjend H. Hasan Basry No.1, Banjarmasin, Kalimantan Selatan</p>
            <p style="margin:0; font-size:9px">Telepon: (0511) 3300741 | Email: rsudansarisaleh@kalselprov.go.id</p>
        </div>
    </div>

    <h3 style="text-align:center; text-decoration: underline; margin-top: 20px;">{{ $title }}</h3>

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
        <strong>( {{ $admin }} )</strong>
    </div>
</body>
</html>