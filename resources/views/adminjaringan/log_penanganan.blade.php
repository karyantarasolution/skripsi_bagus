<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Log Penanganan Insiden</h2>
                <p class="text-xs text-gray-400 mt-1">Dokumentasi teknis seluruh aktivitas penanganan insiden keamanan</p>
            </div>
            <a href="{{ route('adminjaringan.log.penanganan.cetak') }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold rounded-xl hover:from-cyan-500 hover:to-blue-500 transition-all flex items-center shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
        </div>
    </x-slot>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
            <p class="text-xs text-gray-500 uppercase font-black">Deteksi Otomatis</p>
            <h3 class="text-3xl font-bold text-cyan-400 mt-2">{{ $totalOtomatis }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-yellow-500">
            <p class="text-xs text-gray-500 uppercase font-black">Intervensi Manual Admin</p>
            <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ $totalManual }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-red-500">
            <p class="text-xs text-gray-500 uppercase font-black">Total Berhasil Diblokir</p>
            <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $totalBlocked }}</h3>
        </div>
    </div>

    <!-- Log Penanganan -->
    <div class="bg-[#161821] rounded-2xl border border-gray-800 shadow-lg overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h4 class="text-sm font-bold text-white">Riwayat Penanganan Insiden</h4>
            <span class="text-[10px] text-gray-500 font-mono">{{ $semuaLog->count() }} entri</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] uppercase text-gray-500 bg-[#0b0c10] tracking-widest border-b border-gray-800">
                        <th class="px-4 py-4 font-bold">Waktu Kejadian</th>
                        <th class="px-4 py-4 font-bold">IP Address</th>
                        <th class="px-4 py-4 font-bold">Jenis Intrusi</th>
                        <th class="px-4 py-4 font-bold">Endpoint</th>
                        <th class="px-4 py-4 font-bold">Risk Level</th>
                        <th class="px-4 py-4 font-bold">Tindakan Sistem</th>
                        <th class="px-4 py-4 font-bold">Tindakan Admin</th>
                        <th class="px-4 py-4 font-bold">Alasan / Catatan</th>
                        <th class="px-4 py-4 font-bold text-center">Sumber</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-mono">
                    @forelse($semuaLog as $log)
                    <tr class="border-b border-gray-800/50 hover:bg-[#1a1c26] transition-colors">
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($log['waktu'])->format('d/m/Y H:i:s') }}</td>
                        <td class="px-4 py-3 text-cyan-400 font-bold">{{ $log['ip_address'] }}</td>
                        <td class="px-4 py-3">{{ $log['jenis_intrusi'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $log['endpoint'] }}</td>
                        <td class="px-4 py-3">
                            @if($log['risk_level'] == 'Critical')
                                <span class="text-red-500 font-bold">{{ $log['risk_level'] }}</span>
                            @elseif($log['risk_level'] == 'High')
                                <span class="text-orange-500 font-bold">{{ $log['risk_level'] }}</span>
                            @elseif($log['risk_level'] == 'Medium')
                                <span class="text-yellow-500">{{ $log['risk_level'] }}</span>
                            @elseif($log['risk_level'] == 'Low')
                                <span class="text-green-500">{{ $log['risk_level'] }}</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($log['tindakan_sistem'] == 'Blocked' || $log['tindakan_sistem'] == 'Dropped')
                                <span class="text-red-500 font-bold">{{ $log['tindakan_sistem'] }}</span>
                            @else
                                <span class="text-gray-400">{{ $log['tindakan_sistem'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($log['tindakan_admin'] != '-')
                                <span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 rounded font-bold text-[9px]">{{ $log['tindakan_admin'] }}</span>
                            @else
                                <span class="text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">{{ $log['alasan'] }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($log['jenis_log'] == 'manual')
                                <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded font-bold text-[9px]">MANUAL</span>
                            @else
                                <span class="px-2 py-0.5 bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 rounded font-bold text-[9px]">OTOMATIS</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-600 italic">Belum ada data penanganan insiden.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
