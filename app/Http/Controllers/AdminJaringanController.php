<?php

namespace App\Http\Controllers;

use App\Models\AttackLog;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Models\ManualAction;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use App\Services\NetworkScanner;

class AdminJaringanController extends Controller
{
    public function dashboard()
    {
        $totalSerangan = AttackLog::count();
        $totalBlocked = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();
        $totalRules = Rule::count();
        $todayAttacks = AttackLog::whereDate('created_at', today())->count();

        $kategoriData = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get();

        $trenSerangan = AttackLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $recentAttacks = AttackLog::latest()->take(5)->get();

        $riskDistribution = AttackLog::select('risk_level', DB::raw('count(*) as total'))
            ->groupBy('risk_level')
            ->get();

        $scanner = app(NetworkScanner::class);
        $networkInfo = $scanner->getNetworkInfo();

        return view('adminjaringan.dashboard', compact(
            'totalSerangan', 'totalBlocked', 'totalRules', 'todayAttacks',
            'kategoriData', 'trenSerangan', 'recentAttacks', 'riskDistribution',
            'networkInfo'
        ));
    }

    public function liveTraffic()
    {
        $scanner = app(NetworkScanner::class);
        $devices = $scanner->scanNetwork();
        $networkInfo = $scanner->getNetworkInfo();
        $logs = AttackLog::latest()->take(20)->get();

        $traffic = $this->buildTrafficList($devices, $logs);

        return view('adminjaringan.traffic', compact('traffic', 'networkInfo'));
    }

    public function liveTrafficAjax()
    {
        $scanner = app(NetworkScanner::class);
        $devices = $scanner->scanNetwork();
        $networkInfo = $scanner->getNetworkInfo();
        $logs = AttackLog::latest()->take(30)->get();

        $traffic = $this->buildTrafficList($devices, $logs);

        return response()->json([
            'traffic' => $traffic,
            'network_info' => $networkInfo,
            'attack_count' => AttackLog::count(),
            'device_count' => count($devices),
            'new_today' => AttackLog::whereDate('created_at', today())->count(),
        ]);
    }

    protected function buildTrafficList(array $devices, $logs): array
    {
        $result = [];

        foreach ($devices as $device) {
            $hasAttack = $logs->firstWhere('ip_address', $device['ip']);

            if ($hasAttack) {
                $result[] = [
                    'time' => $hasAttack->created_at->format('H:i:s'),
                    'ip_address' => $hasAttack->ip_address,
                    'endpoint' => $hasAttack->endpoint,
                    'kategori' => $hasAttack->kategori,
                    'risk_level' => $hasAttack->risk_level,
                    'action_taken' => $hasAttack->action_taken,
                    'origin' => $device['type'],
                    'mac' => $device['mac'],
                    'type' => 'attack',
                ];
            } else {
                $result[] = [
                    'time' => now()->format('H:i:s'),
                    'ip_address' => $device['ip'],
                    'endpoint' => '-',
                    'kategori' => 'Normal Traffic',
                    'risk_level' => '-',
                    'action_taken' => 'Allowed',
                    'origin' => $device['type'],
                    'mac' => $device['mac'],
                    'type' => 'normal',
                ];
            }
        }

        foreach ($logs as $log) {
            $exists = collect($result)->firstWhere('ip_address', $log->ip_address);
            if (!$exists) {
                $result[] = [
                    'time' => $log->created_at->format('H:i:s'),
                    'ip_address' => $log->ip_address,
                    'endpoint' => $log->endpoint,
                    'kategori' => $log->kategori,
                    'risk_level' => $log->risk_level,
                    'action_taken' => $log->action_taken,
                    'origin' => $log->origin ?? 'External',
                    'mac' => '-',
                    'type' => 'attack',
                ];
            }
        }

        usort($result, function ($a, $b) {
            if ($a['type'] === 'attack' && $b['type'] !== 'attack') return -1;
            if ($a['type'] !== 'attack' && $b['type'] === 'attack') return 1;
            return 0;
        });

        return $result;
    }

    public function logIntrusi()
    {
        $logs = AttackLog::latest()->paginate(20);
        return view('adminjaringan.log', compact('logs'));
    }

    public function action()
    {
        $recentAttacks = AttackLog::latest()->take(5)->get();
        return view('adminjaringan.action', compact('recentAttacks'));
    }

    public function processAction(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'action_type' => 'required|in:block,whitelist,drop',
            'reason' => 'required|string|max:255'
        ]);

        ManualAction::create([
            'ip_address' => $request->ip_address,
            'action_type' => $request->action_type,
            'reason' => $request->reason,
        ]);

        \Illuminate\Support\Facades\Cache::forget('ids_blocked_ips');

        if (in_array($request->action_type, ['block', 'drop'])) {
            AttackLog::where('ip_address', $request->ip_address)
                      ->where('action_taken', '!=', 'Blocked')
                      ->update(['action_taken' => 'Blocked']);
        }

        return back()->with('success', "IP {$request->ip_address} berhasil diproses dengan tindakan: {$request->action_type}");
    }

    public function networkScan()
    {
        $scanner = app(NetworkScanner::class);
        $devices = $scanner->fullScan();
        $networkInfo = $scanner->getNetworkInfo();

        return view('adminjaringan.network', compact('devices', 'networkInfo'));
    }

    public function networkScanAjax()
    {
        $scanner = app(NetworkScanner::class);
        $devices = $scanner->fullScan();
        $networkInfo = $scanner->getNetworkInfo();

        return response()->json([
            'devices' => $devices,
            'network_info' => $networkInfo,
            'total_devices' => count($devices),
            'active_devices' => collect($devices)->where('status', 'active')->count(),
        ]);
    }

    // ==========================================
    // RESET DATA & TEST REAL-TIME
    // ==========================================
    public function resetData()
    {
        AttackLog::truncate();
        ManualAction::truncate();
        \Illuminate\Support\Facades\Cache::forget('ids_rules');
        \Illuminate\Support\Facades\Cache::forget('network_scan_results');

        return back()->with('success', 'Semua data attack log & manual action berhasil direset. Sistem siap mendeteksi serangan real-time.');
    }

    public function testAttack()
    {
        $detector = app(\App\Services\AttackDetector::class);

        $testPatterns = [
            ['url' => '?q=<script>alert("XSS")</script>', 'desc' => 'XSS Attack'],
            ['url' => '?search=UNION+SELECT+*+FROM+users', 'desc' => 'SQL Injection'],
            ['url' => '?file=../../../etc/passwd', 'desc' => 'Path Traversal'],
            ['url' => '?cmd=cmd.exe+/c+dir', 'desc' => 'Remote Command Execution'],
            ['url' => '?input=eval(base64_decode("test"))', 'desc' => 'Code Injection'],
        ];

        $pattern = $testPatterns[array_rand($testPatterns)];

        $request = \Illuminate\Http\Request::create(
            '/test-attack' . $pattern['url'],
            'GET',
            ['q' => str_replace('?q=', '', $pattern['url'])]
        );

        $attackData = $detector->inspect($request);
        if ($attackData) {
            $detector->logAttack($attackData);
            return response()->json([
                'success' => true,
                'message' => "Real-time detection berhasil! {$pattern['desc']} terdeteksi.",
                'data' => [
                    'ip' => $attackData['ip_address'],
                    'kategori' => $attackData['kategori'],
                    'pola' => $attackData['pola_terdeteksi'],
                    'risk' => $attackData['risk_level'],
                    'action' => $attackData['action_taken'],
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada serangan terdeteksi.']);
    }

    // ==========================================
    // LAPORAN UTAMA
    // ==========================================
    public function laporanMenu()
    {
        return view('adminjaringan.laporan');
    }

    public function cetakLaporan($tipe)
    {
        $admin = auth()->user()->name;

        switch ($tipe) {
            case 'manual':
                $logs = ManualAction::latest()->get();
                $title = "Laporan Intervensi Admin Manual";
                $totalData = $logs->count();

                $mappedLogs = $logs->map(function ($action) {
                    return (object) [
                        'id' => $action->id,
                        'created_at' => $action->created_at,
                        'ip_address' => $action->ip_address,
                        'kategori' => 'Manual Action',
                        'pola_terdeteksi' => '-',
                        'endpoint' => '-',
                        'origin' => 'Admin',
                        'risk_level' => '-',
                        'action_taken' => strtoupper($action->action_type),
                        'deskripsi' => $action->reason,
                    ];
                });

                return Pdf::loadView('adminjaringan.pdf_laporan', [
                    'logs' => $mappedLogs,
                    'title' => $title,
                    'admin' => $admin,
                    'totalData' => $totalData,
                    'totalKategori' => collect(['Manual Action' => $totalData]),
                    'totalRisk' => collect(),
                    'tipe' => $tipe,
                ])->setPaper('a4', 'landscape')->stream();

            case 'all':
                $query = AttackLog::query();
                $title = "Seluruh Log Intrusi Jaringan";
                break;
            case 'sqli':
                $query = AttackLog::where('kategori', 'SQL Injection');
                $title = "Laporan Serangan SQL Injection";
                break;
            case 'xss':
                $query = AttackLog::where('kategori', 'XSS');
                $title = "Laporan Serangan XSS";
                break;
            case 'blocked':
                $query = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped']);
                $title = "Daftar IP Terblokir";
                break;
            case 'critical':
                $query = AttackLog::where('risk_level', 'Critical');
                $title = "Laporan Ancaman Critical";
                break;
            case 'normal':
                $query = AttackLog::where('action_taken', 'Allowed');
                $title = "Laporan Trafik Normal";
                break;
            case 'today':
                $query = AttackLog::whereDate('created_at', Carbon::today());
                $title = "Laporan Aktivitas Hari Ini";
                break;
            default:
                $query = AttackLog::query();
                $title = "Laporan Keamanan Jaringan";
                break;
        }

        $logs = $query->latest()->get();
        $totalData = $logs->count();
        $totalKategori = $logs->groupBy('kategori')->map->count();
        $totalRisk = $logs->groupBy('risk_level')->map->count();

        return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin', 'totalData', 'totalKategori', 'totalRisk', 'tipe'))
                  ->setPaper('a4', 'landscape')->stream();
    }

    // ==========================================
    // LAPORAN ANALITIK STATISTIK ANOMALI
    // ==========================================
    public function laporanAnalitik()
    {
        $statKategori = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $totalSerangan = AttackLog::count();

        $statKategori = $statKategori->map(function ($item) use ($totalSerangan) {
            $item->persentase = $totalSerangan > 0 ? round(($item->total / $totalSerangan) * 100, 1) : 0;
            return $item;
        });

        $dominant = $statKategori->first();

        $harian = AttackLog::select(DB::raw('DATE(created_at) as date'), 'kategori', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date', 'kategori')
            ->orderBy('date')
            ->get();

        $bulanan = AttackLog::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"), 'kategori', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan', 'kategori')
            ->orderBy('bulan')
            ->get();

        $trendRisk = AttackLog::select('risk_level', DB::raw('count(*) as total'))
            ->groupBy('risk_level')
            ->orderByDesc('total')
            ->get();

        return view('adminjaringan.laporan_analitik', compact(
            'statKategori', 'totalSerangan', 'dominant',
            'harian', 'bulanan', 'trendRisk'
        ));
    }

    public function cetakLaporanAnalitik()
    {
        $admin = auth()->user()->name;

        $statKategori = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $totalSerangan = AttackLog::count();

        $statKategori = $statKategori->map(function ($item) use ($totalSerangan) {
            $item->persentase = $totalSerangan > 0 ? round(($item->total / $totalSerangan) * 100, 1) : 0;
            return $item;
        });

        $dominant = $statKategori->first();

        $trendRisk = AttackLog::select('risk_level', DB::raw('count(*) as total'))
            ->groupBy('risk_level')
            ->orderByDesc('total')
            ->get();

        $title = "Laporan Analitik Statistik Anomali Keamanan";

        return Pdf::loadView('adminjaringan.pdf_analitik', compact(
            'statKategori', 'totalSerangan', 'dominant', 'trendRisk', 'admin', 'title'
        ))->setPaper('a4', 'landscape')->stream();
    }

    // ==========================================
    // LOG PENANGANAN INSIDEN
    // ==========================================
    public function logPenanganan()
    {
        $attackLogs = AttackLog::latest()->take(50)->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'waktu' => $log->created_at,
                'ip_address' => $log->ip_address,
                'jenis_intrusi' => $log->kategori,
                'pola_terdeteksi' => $log->pola_terdeteksi,
                'endpoint' => $log->endpoint,
                'risk_level' => $log->risk_level,
                'tindakan_sistem' => $log->action_taken,
                'tindakan_admin' => '-',
                'alasan' => '-',
                'jenis_log' => 'otomatis',
            ];
        });

        $manualLogs = ManualAction::latest()->get()->map(function ($action) {
            return [
                'id' => $action->id,
                'waktu' => $action->created_at,
                'ip_address' => $action->ip_address,
                'jenis_intrusi' => 'Manual Action',
                'pola_terdeteksi' => '-',
                'endpoint' => '-',
                'risk_level' => '-',
                'tindakan_sistem' => '-',
                'tindakan_admin' => strtoupper($action->action_type),
                'alasan' => $action->reason,
                'jenis_log' => 'manual',
            ];
        });

        $semuaLog = collect($attackLogs)->merge($manualLogs)->sortByDesc('waktu');

        $totalOtomatis = AttackLog::count();
        $totalManual = ManualAction::count();
        $totalBlocked = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();

        return view('adminjaringan.log_penanganan', compact(
            'semuaLog', 'totalOtomatis', 'totalManual', 'totalBlocked'
        ));
    }

    public function cetakLogPenanganan()
    {
        $admin = auth()->user()->name;

        $attackLogs = AttackLog::latest()->take(100)->get()->map(function ($log) {
            return [
                'waktu' => $log->created_at,
                'ip_address' => $log->ip_address,
                'jenis_intrusi' => $log->kategori,
                'risk_level' => $log->risk_level,
                'tindakan_sistem' => $log->action_taken,
                'tindakan_admin' => '-',
                'jenis_log' => 'Otomatis',
            ];
        });

        $manualLogs = ManualAction::latest()->get()->map(function ($action) {
            return [
                'waktu' => $action->created_at,
                'ip_address' => $action->ip_address,
                'jenis_intrusi' => 'Intervensi Manual',
                'risk_level' => '-',
                'tindakan_sistem' => '-',
                'tindakan_admin' => strtoupper($action->action_type),
                'jenis_log' => 'Manual',
            ];
        });

        $semuaLog = collect($attackLogs)->merge($manualLogs)->sortByDesc('waktu');
        $title = "Laporan Log Penanganan Insiden Keamanan";

        return Pdf::loadView('adminjaringan.pdf_penanganan', compact('semuaLog', 'admin', 'title'))
            ->setPaper('a4', 'landscape')->stream();
    }

    // ==========================================
    // LAPORAN KETERSEDIAAN INFRASTRUKTUR
    // ==========================================
    public function laporanKetersediaan()
    {
        $totalSerangan = AttackLog::count();
        $totalBlocked = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();

        $firstLog = AttackLog::oldest()->first();
        $lastLog = AttackLog::latest()->first();

        if ($firstLog && $lastLog) {
            $totalHari = $firstLog->created_at->diffInDays($lastLog->created_at) + 1;
            $hariTerkenaSerangan = AttackLog::select(DB::raw('DATE(created_at) as date'))
                ->distinct()->get()->count();
            $hariAman = $totalHari - $hariTerkenaSerangan;
            $availabilityPercent = $totalHari > 0 ? round(($hariAman / $totalHari) * 100, 2) : 100;
        } else {
            $totalHari = 0;
            $hariTerkenaSerangan = 0;
            $hariAman = 0;
            $availabilityPercent = 100;
        }

        $harian = AttackLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $jamSerangan = AttackLog::select(DB::raw('HOUR(created_at) as jam'), DB::raw('count(*) as total'))
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $avgPerHari = $totalHari > 0 ? round($totalSerangan / $totalHari, 1) : 0;

        $lastLogTime = $lastLog ? $lastLog->created_at : now();
        $totalMonitoringHours = $firstLog ? $firstLog->created_at->diffInHours($lastLogTime) : 0;

        return view('adminjaringan.laporan_ketersediaan', compact(
            'totalSerangan', 'totalBlocked', 'totalHari', 'hariTerkenaSerangan',
            'hariAman', 'availabilityPercent', 'harian', 'jamSerangan',
            'avgPerHari', 'totalMonitoringHours'
        ));
    }

    public function cetakLaporanKetersediaan()
    {
        $admin = auth()->user()->name;

        $totalSerangan = AttackLog::count();
        $totalBlocked = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();
        $firstLog = AttackLog::oldest()->first();
        $lastLog = AttackLog::latest()->first();

        if ($firstLog && $lastLog) {
            $totalHari = $firstLog->created_at->diffInDays($lastLog->created_at) + 1;
            $hariTerkenaSerangan = AttackLog::select(DB::raw('DATE(created_at) as date'))
                ->distinct()->get()->count();
            $hariAman = $totalHari - $hariTerkenaSerangan;
            $availabilityPercent = $totalHari > 0 ? round(($hariAman / $totalHari) * 100, 2) : 100;
        } else {
            $totalHari = 0;
            $hariTerkenaSerangan = 0;
            $hariAman = 0;
            $availabilityPercent = 100;
        }

        $avgPerHari = $totalHari > 0 ? round($totalSerangan / $totalHari, 1) : 0;

        $harian = AttackLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $title = "Laporan Ketersediaan Infrastruktur Jaringan";

        return Pdf::loadView('adminjaringan.pdf_ketersediaan', compact(
            'totalSerangan', 'totalBlocked', 'totalHari', 'hariTerkenaSerangan',
            'hariAman', 'availabilityPercent', 'avgPerHari', 'harian', 'admin', 'title'
        ))->setPaper('a4', 'landscape')->stream();
    }
}
