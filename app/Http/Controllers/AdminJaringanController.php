<?php

namespace App\Http\Controllers;

use App\Models\AttackLog;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Models\ManualAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

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

        // Ambil 5 serangan terbaru untuk ditampilkan
        $recentAttacks = AttackLog::latest()->take(5)->get();

        // Hitung risk level distribution
        $riskDistribution = AttackLog::select('risk_level', DB::raw('count(*) as total'))
            ->groupBy('risk_level')
            ->get();

        return view('adminjaringan.dashboard', compact(
            'totalSerangan', 'totalBlocked', 'totalRules', 'todayAttacks',
            'kategoriData', 'trenSerangan', 'recentAttacks', 'riskDistribution'
        ));
    }

    public function liveTraffic()
    {
        $logs = AttackLog::latest()->take(15)->get();
        return view('adminjaringan.traffic', compact('logs'));
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

        AttackLog::where('ip_address', $request->ip_address)
                  ->update(['action_taken' => 'Blocked']);

        return back()->with('success', "IP {$request->ip_address} berhasil diproses dengan tindakan: {$request->action_type}");
    }

    public function laporanMenu()
    {
        return view('adminjaringan.laporan');
    }

    public function cetakLaporan($tipe)
    {
        $query = AttackLog::query();
        $admin = auth()->user()->name;

        switch ($tipe) {
            case 'all': $title = "Seluruh Log Intrusi Jaringan"; break;
            case 'sqli': $query->where('kategori', 'SQL Injection'); $title = "Laporan Serangan SQL Injection"; break;
            case 'xss': $query->where('kategori', 'XSS Attack'); $title = "Laporan Serangan XSS"; break;
            case 'blocked': $query->whereIn('action_taken', ['Blocked', 'Dropped']); $title = "Daftar IP Terblokir"; break;
            case 'critical': $query->where('risk_level', 'Critical'); $title = "Laporan Ancaman Critical"; break;
            case 'manual':
                $logs = ManualAction::latest()->get();
                $title = "Laporan Intervensi Admin Manual";
                return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin'))->setPaper('a4', 'landscape')->stream();
            case 'normal': $query->where('action_taken', 'Allowed'); $title = "Laporan Trafik Normal"; break;
            case 'today': $query->whereDate('created_at', Carbon::today()); $title = "Laporan Aktivitas Hari Ini"; break;
        }

        $logs = $query->latest()->get();

        // Hitung metadata untuk proses laporan
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
        // Statistik per kategori (total keseluruhan)
        $statKategori = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $totalSerangan = AttackLog::count();

        // Tambahkan persentase
        $statKategori = $statKategori->map(function ($item) use ($totalSerangan) {
            $item->persentase = $totalSerangan > 0 ? round(($item->total / $totalSerangan) * 100, 1) : 0;
            return $item;
        });

        // Kategori paling dominan
        $dominant = $statKategori->first();

        // Statistik harian (7 hari terakhir)
        $harian = AttackLog::select(DB::raw('DATE(created_at) as date'), 'kategori', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date', 'kategori')
            ->orderBy('date')
            ->get();

        // Statistik bulanan (12 bulan terakhir)
        $bulanan = AttackLog::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"), 'kategori', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan', 'kategori')
            ->orderBy('bulan')
            ->get();

        // Trend risk level
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
        // Gabungkan AttackLog + ManualAction untuk riwayat penanganan
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

        // Gabung dan urutkan berdasarkan waktu
        $semuaLog = collect($attackLogs)->merge($manualLogs)->sortByDesc('waktu');

        // Statistik ringkasan
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

        // Hitung rentang waktu data
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

        // Data harian untuk chart
        $harian = AttackLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Data distribusi jam sibuk serangan
        $jamSerangan = AttackLog::select(DB::raw('HOUR(created_at) as jam'), DB::raw('count(*) as total'))
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        // Rata-rata serangan per hari
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
