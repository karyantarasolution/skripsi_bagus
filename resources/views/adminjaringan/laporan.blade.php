<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-wide">Pusat Laporan Keamanan Jaringan</h2>
        <p class="text-xs text-gray-400 mt-1">Sistem Deteksi Intrusi RSUD Ansari Saleh</p>
    </x-slot>

    <div class="mt-8 space-y-8 px-4">
        <!-- Laporan Data Intrusi -->
        <div>
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Laporan Data Intrusi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                   class="group bg-[#161821] p-5 rounded-2xl border border-gray-800 hover:border-{{ $menu['color'] }}-500/50 transition-all shadow-lg text-center">
                    <div class="mx-auto w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">{{ $menu['name'] }}</h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">{{ $menu['desc'] }}</p>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Laporan Analitik & Evaluasi -->
        <div>
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Laporan Analitik & Evaluasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('adminjaringan.laporan.analitik') }}"
                   class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-purple-500/50 transition-all shadow-lg text-center">
                    <div class="mx-auto w-12 h-12 bg-purple-500/10 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">Statistik Anomali</h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">Analitik frekuensi ancaman</p>
                </a>

                <a href="{{ route('adminjaringan.log.penanganan') }}"
                   class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-yellow-500/50 transition-all shadow-lg text-center">
                    <div class="mx-auto w-12 h-12 bg-yellow-500/10 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">Log Penanganan Insiden</h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">Dokumentasi teknis mitigasi</p>
                </a>

                <a href="{{ route('adminjaringan.laporan.ketersediaan') }}"
                   class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-green-500/50 transition-all shadow-lg text-center">
                    <div class="mx-auto w-12 h-12 bg-green-500/10 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">Ketersediaan Infrastruktur</h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">Evaluasi performa sistem</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
