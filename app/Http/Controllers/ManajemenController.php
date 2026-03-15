<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttackLog;
use App\Models\ManualAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class ManajemenController extends Controller
{
    public function dashboard() 
    {
        $data['total_serangan'] = AttackLog::count();
        $data['total_blocked'] = AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();
        $data['total_user'] = \App\Models\User::count();
        $data['chart_kategori'] = AttackLog::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')->get();

        return view('manajemen.dashboard', $data); 
    }

    public function downloadLaporan() { return view('manajemen.laporan'); }

    // TAMBAHKAN INI JIKA BELUM ADA
    public function cetakLaporan($tipe)
    {
        $query = AttackLog::query();
        $admin = auth()->user()->name;

        switch ($tipe) {
            case 'all': $title = "Seluruh Log Intrusi Jaringan"; break;
            case 'sqli': $query->where('kategori', 'SQL Injection'); $title = "Laporan Khusus SQL Injection"; break;
            case 'xss': $query->where('kategori', 'XSS Attack'); $title = "Laporan Khusus XSS Attack"; break;
            case 'blocked': $query->whereIn('action_taken', ['Blocked', 'Dropped']); $title = "Daftar Hitam IP Terblokir"; break;
            case 'critical': $query->where('risk_level', 'Critical'); $title = "Laporan Ancaman Tingkat Critical"; break;
            case 'manual': 
                $logs = ManualAction::latest()->get(); 
                $title = "Laporan Intervensi Admin Manual";
                return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin'))
                          ->setPaper('a4', 'landscape')->stream();
            case 'normal': $query->where('action_taken', 'Allowed'); $title = "Laporan Trafik Jaringan Normal"; break;
            case 'today': $query->whereDate('created_at', Carbon::today()); $title = "Laporan Aktivitas Hari Ini"; break;
            default: $title = "Laporan Keamanan Sistem"; break;
        }

        $logs = $query->latest()->get();

        // Kita panggil view PDF yang sudah ada kop suratnya tadi
        return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin'))
                  ->setPaper('a4', 'landscape')
                  ->stream("Laporan_{$tipe}_".date('Ymd').".pdf");
    }
}