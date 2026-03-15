<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-wide">Pusat Laporan Keamanan Jaringan</h2>
        <p class="text-xs text-gray-400 mt-1">Sistem Deteksi Intrusi RSUD Ansari Saleh</p>
    </x-slot>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
        @php
            $menus = [
                ['id' => 'all', 'name' => 'Seluruh Log', 'color' => 'blue', 'desc' => 'Rekap semua aktivitas'],
                ['id' => 'sqli', 'name' => 'Serangan SQLi', 'color' => 'purple', 'desc' => 'Khusus SQL Injection'],
                ['id' => 'xss', 'name' => 'Serangan XSS', 'color' => 'indigo', 'desc' => 'Khusus Scripting'],
                ['id' => 'blocked', 'name' => 'IP Terblokir', 'color' => 'red', 'desc' => 'Daftar Hitam Sistem'],
                ['id' => 'critical', 'name' => 'Level Critical', 'color' => 'pink', 'desc' => 'Ancaman Bahaya'],
                ['id' => 'manual', 'name' => 'Aksi Manual', 'color' => 'yellow', 'desc' => 'Intervensi Admin'],
                ['id' => 'normal', 'name' => 'Trafik Normal', 'color' => 'green', 'desc' => 'Aktivitas Aman'],
                ['id' => 'today', 'name' => 'Rekap Hari Ini', 'color' => 'cyan', 'desc' => 'Kejadian 24 Jam'],
            ];
        @endphp

        @foreach($menus as $menu)
        <a href="{{ route('adminjaringan.laporan.cetak', $menu['id']) }}" target="_blank" 
           class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-{{ $menu['color'] }}-500/50 transition-all shadow-lg text-center">
            <div class="mx-auto w-12 h-12 bg-{{ $menu['color'] }}-500/10 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-{{ $menu['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h4 class="text-sm font-bold text-white">{{ $menu['name'] }}</h4>
            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">{{ $menu['desc'] }}</p>
        </a>
        @endforeach
    </div>
</x-app-layout>