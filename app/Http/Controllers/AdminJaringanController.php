<?php

namespace App\Http\Controllers;

use App\Models\AttackLog;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Models\ManualAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
class AdminJaringanController extends Controller
{
   public function dashboard()
{
    // Hitung total untuk kartu statistik
    $totalSerangan = \App\Models\AttackLog::count();
    $totalBlocked = \App\Models\AttackLog::whereIn('action_taken', ['Blocked', 'Dropped'])->count();
    $totalRules = \App\Models\Rule::count();
    $todayAttacks = \App\Models\AttackLog::whereDate('created_at', today())->count();

    // DATA UNTUK PIE CHART (Kategori Serangan)
    $kategoriData = \App\Models\AttackLog::select('kategori', \DB::raw('count(*) as total'))
        ->groupBy('kategori')
        ->get();

    // DATA UNTUK BAR CHART (7 Hari Terakhir)
    $trenSerangan = \App\Models\AttackLog::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'))
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    return view('adminjaringan.dashboard', compact(
        'totalSerangan', 'totalBlocked', 'totalRules', 'todayAttacks',
        'kategoriData', 'trenSerangan'
    ));}

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
    // Ambil serangan terbaru untuk list di kanan (Ancaman Terbaru)
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

    // Simpan ke tabel manual_actions
    ManualAction::create([
        'ip_address' => $request->ip_address,
        'action_type' => $request->action_type,
        'reason' => $request->reason,
    ]);

    // OPSIONAL: Jika di-block, kita bisa update tabel attack_logs agar statusnya sinkron
    AttackLog::where('ip_address', $request->ip_address)
              ->update(['action_taken' => 'Blocked']);

    return back()->with('success', "IP {$request->ip_address} berhasil diproses dengan tindakan: {$request->action_type}");
}
public function laporanMenu() {
    return view('adminjaringan.laporan');
}

public function cetakLaporan($tipe) {
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
    return Pdf::loadView('adminjaringan.pdf_laporan', compact('logs', 'title', 'admin'))
              ->setPaper('a4', 'landscape')->stream();
}
}