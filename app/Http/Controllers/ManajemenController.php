<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttackLog;
use App\Models\ManualAction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class ManajemenController extends Controller
{
    public function dashboard()
    {
        $total_serangan = AttackLog::count();
        $total_blocked = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();
        $total_user = User::count();

        $chart_kategori = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')->get();

        // Tambahan statistik untuk dashboard eksekutif
        $total_critical = AttackLog::where('risk_level', 'Critical')->count();
        $total_hari_ini = AttackLog::whereDate('created_at', Carbon::today())->count();

        $tren_mingguan = AttackLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('manajemen.dashboard', compact(
            'total_serangan', 'total_blocked', 'total_user',
            'chart_kategori', 'total_critical', 'total_hari_ini',
            'tren_mingguan'
        ));
    }

    public function downloadLaporan()
    {
        return view('manajemen.laporan');
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
                $title = "Laporan Khusus SQL Injection";
                break;
            case 'xss':
                $query = AttackLog::where('kategori', 'XSS');
                $title = "Laporan Khusus XSS Attack";
                break;
            case 'blocked':
                $query = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped']);
                $title = "Daftar Hitam IP Terblokir";
                break;
            case 'critical':
                $query = AttackLog::where('risk_level', 'Critical');
                $title = "Laporan Ancaman Tingkat Critical";
                break;
            case 'normal':
                $query = AttackLog::where('action_taken', 'Allowed');
                $title = "Laporan Trafik Jaringan Normal";
                break;
            case 'today':
                $query = AttackLog::whereDate('created_at', Carbon::today());
                $title = "Laporan Aktivitas Hari Ini";
                break;
            default:
                $query = AttackLog::query();
                $title = "Laporan Keamanan Sistem";
                break;
        }

        $logs = $query->latest()->get();
        $totalData = $logs->count();
        $totalKategori = $logs->groupBy('kategori')->map->count();

        return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin', 'totalData', 'totalKategori', 'tipe'))
                  ->setPaper('a4', 'landscape')
                  ->stream("Laporan_{$tipe}_" . date('Ymd') . ".pdf");
    }
}
