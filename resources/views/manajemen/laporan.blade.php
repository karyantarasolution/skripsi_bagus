<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-tight">Pusat Unduhan Laporan</h2>
        <p class="text-xs text-gray-400">Pilih format laporan PDF yang ingin diunduh untuk arsip</p>
    </x-slot>

    <div class="py-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $files = [
                ['id' => 'all', 'title' => 'Master Log Seluruhnya', 'color' => 'blue'],
                ['id' => 'sqli', 'title' => 'Rekap SQL Injection', 'color' => 'cyan'],
                ['id' => 'xss', 'title' => 'Rekap XSS Attack', 'color' => 'indigo'],
                ['id' => 'blocked', 'title' => 'Data IP Blacklist', 'color' => 'red'],
                ['id' => 'critical', 'title' => 'Log Tingkat Critical', 'color' => 'pink'],
                ['id' => 'manual', 'title' => 'Log Aksi Admin', 'color' => 'yellow'],
                ['id' => 'normal', 'title' => 'Laporan Trafik Normal', 'color' => 'green'],
                ['id' => 'today', 'title' => 'Laporan Harian (Today)', 'color' => 'orange'],
            ];
        @endphp

    @foreach($files as $file)
<a href="{{ route('manajemen.laporan.cetak', $file['id']) }}" target="_blank" 
   class="group bg-[#161821] p-6 rounded-2xl border border-gray-800 hover:border-{{ $file['color'] }}-500 transition-all shadow-lg">
    <div class="flex flex-col items-center">
        <h5 class="text-xs font-bold text-white uppercase">{{ $file['title'] }}</h5>
    </div>
</a>
@endforeach
    </div>
</x-app-layout>