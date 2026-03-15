<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule as ValidationRule;
use App\Models\Setting;
class SuperAdminController extends Controller
{
    // ==========================================
    // BAGIAN 1: DASHBOARD
    // ==========================================
    public function dashboard()
    {
        $totalRules = Rule::count(); 
        
        // Nanti ganti dengan data real dari tabel log_intrusi
        $totalBlocked = 1204; 
        $activeStaff = User::where('role', 'admin_jaringan')->count(); 

        // Nanti ganti dengan data real traffic per hari
        $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $chartData = [12, 19, 3, 5, 2, 3, 15];

        return view('superadmin.dashboard', compact(
            'totalRules', 
            'totalBlocked', 
            'activeStaff', 
            'chartLabels', 
            'chartData'
        ));
    }

    // ==========================================
    // BAGIAN 2: MANAJEMEN USER
    // ==========================================
    public function manajemenUser() 
    {
        $users = User::latest()->paginate(10);
        return view('superadmin.users', compact('users'));
    }

    public function createUser() 
    { 
        return view('superadmin.user-create'); 
    }

    public function storeUser(Request $request) 
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:super_admin,admin_jaringan,manajemen'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('superadmin.users')->with('success', 'Data akun berhasil ditambahkan.');
    }

    public function editUser(User $user) 
    { 
        return view('superadmin.user-edit', compact('user')); 
    }

    public function updateUser(Request $request, User $user) 
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', ValidationRule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:super_admin,admin_jaringan,manajemen'],
        ]);

        $data = $request->only('name', 'email', 'role');

        // Jika password diisi, maka update passwordnya
        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('superadmin.users')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroyUser(User $user) 
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('superadmin.users')->with('success', 'Data akun berhasil dihapus.');
    }

    // ==========================================
    // BAGIAN 3: MANAJEMEN RULE SIGNATURE
    // ==========================================
   public function manajemenRule() 
    {
        $rules = Rule::latest()->paginate(10);
        $totalRules = Rule::count();
        // Sesuai screenshot: resources/views/superadmin/rules.blade.php
        return view('superadmin.rules', compact('rules', 'totalRules'));
    }

    public function createRule() 
    { 
        // Sesuai screenshot: resources/views/superadmin/rule-create.blade.php
        return view('superadmin.rule-create'); 
    }

    public function storeRule(Request $request) 
    {
        $request->validate([
            'kategori' => 'required|string',
            'pola' => 'required|string|unique:rules,pola',
            'deskripsi' => 'nullable|string'
        ]);

        Rule::create($request->all());
        return redirect()->route('superadmin.rules')->with('success', 'Rule baru berhasil ditambahkan.');
    }

    public function editRule(Rule $rule) 
    { 
        // Sesuai screenshot: resources/views/superadmin/rule-edit.blade.php
        return view('superadmin.rule-edit', compact('rule')); 
    }

    public function updateRule(Request $request, Rule $rule) 
    {
        $request->validate([
            'kategori' => 'required|string',
            'pola' => ['required', 'string', ValidationRule::unique('rules')->ignore($rule->id)],
            'deskripsi' => 'nullable|string'
        ]);

        $rule->update($request->all());
        return redirect()->route('superadmin.rules')->with('success', 'Rule berhasil diperbarui.');
    }

    public function destroyRule(Rule $rule) 
    {
        $rule->delete();
        return redirect()->route('superadmin.rules')->with('success', 'Rule berhasil dihapus.');
    }

    // ==========================================
    // BAGIAN 4: SETTING JARINGAN
    // ==========================================
public function settingJaringan() 
{
    // Ambil semua data dari tabel settings dan ubah jadi array key => value
    $config = Setting::pluck('value', 'key')->toArray();

    // Beri nilai default jika database masih kosong biar nggak error
    $config = array_merge([
        'server_ip' => '127.0.0.1',
        'max_request' => '100',
        'alert_email' => 'admin@example.com',
        'whitelist' => '127.0.0.1',
        'ids_status' => 'active'
    ], $config);

    return view('superadmin.setting', compact('config'));
}

public function updateSetting(Request $request)
{
    $data = $request->except(['_token', '_method']);

    // Loop semua input dan simpan ke database (Update jika ada, Create jika belum ada)
    foreach ($data as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    return redirect()->back()->with('success', 'Konfigurasi sistem telah dipermanenkan ke database.');
}
}