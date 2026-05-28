<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-tight">Pusat Unduhan Laporan</h2>
        <p class="text-xs text-gray-400">Pilih format laporan PDF yang ingin diunduh untuk arsip</p>
    </x-slot>

    <div class="py-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $files = [
                ['id' => 'all', 'title' => 'Master Log Seluruhnya', 'color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['id' => 'sqli', 'title' => 'Rekap SQL Injection', 'color' => 'cyan', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                ['id' => 'xss', 'title' => 'Rekap XSS Attack', 'color' => 'indigo', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                ['id' => 'blocked', 'title' => 'Data IP Blacklist', 'color' => 'red', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
                ['id' => 'critical', 'title' => 'Log Tingkat Critical', 'color' => 'pink', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['id' => 'manual', 'title' => 'Log Aksi Admin', 'color' => 'yellow', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['id' => 'normal', 'title' => 'Laporan Trafik Normal', 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['id' => 'today', 'title' => 'Laporan Harian (Today)', 'color' => 'orange', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        @foreach($files as $file)
        <a href="{{ route('manajemen.laporan.cetak', $file['id']) }}" target="_blank"
           class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-{{ $file['color'] }}-500 transition-all shadow-lg text-center">
            <div class="mx-auto w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-{{ $file['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $file['icon'] }}"></path>
                </svg>
            </div>
            <h5 class="text-sm font-bold text-white uppercase">{{ $file['title'] }}</h5>
            <p class="text-[10px] text-gray-500 mt-2">Klik untuk unduh PDF</p>
        </a>
        @endforeach
    </div>
</x-app-layout>
