<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Live Traffic Monitoring</h2>
                <p class="text-xs text-gray-400 mt-1">Pemantauan paket data masuk secara realtime</p>
            </div>
            <div class="px-4 py-2 bg-red-500/10 border border-red-500/30 rounded-xl">
                <span class="text-red-500 text-[10px] font-black tracking-widest uppercase animate-pulse">IDS Engine Scanning...</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">
        <div class="bg-[#0b0c10] rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
            <div class="bg-[#161821] px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-300 flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3 animate-ping"></span>
                    Recent Network Anomalies
                </h3>
                <span class="text-[10px] font-mono text-gray-500">Uptime: 14h 22m 05s</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 bg-[#0b0c10] tracking-widest border-b border-gray-800">
                            <th class="px-6 py-4 font-bold">Timestamp</th>
                            <th class="px-6 py-4 font-bold">Source IP</th>
                            <th class="px-6 py-4 font-bold">Target Endpoint</th>
                            <th class="px-6 py-4 font-bold">Detection Type</th>
                            <th class="px-6 py-4 font-bold">Severity</th>
                            <th class="px-6 py-4 font-bold text-center">System Action</th>
                        </tr>
                    </thead>
                  <tbody class="text-xs font-mono">
    @forelse($logs as $log)
    <tr class="border-b border-gray-800/50 hover:bg-gray-800/20 transition-colors">
        <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('H:i:s') }}</td>
        
        <td class="px-6 py-4 text-cyan-400 font-bold">{{ $log->ip_address }}</td>
        
        <td class="px-6 py-4 text-gray-300">{{ $log->endpoint }}</td>
        
        <td class="px-6 py-4">
            @if($log->kategori !== 'Normal Traffic')
                <span class="text-pink-500 font-bold">{{ $log->kategori }}</span>
            @else
                <span class="text-gray-500">{{ $log->kategori }}</span>
            @endif
        </td>
        
        <td class="px-6 py-4">
            @if($log->risk_level == 'Critical' || $log->risk_level == 'High')
                <span class="px-2 py-0.5 bg-red-500/20 text-red-500 rounded border border-red-500/30 font-bold text-[9px]">
                    {{ strtoupper($log->risk_level) }}
                </span>
            @else
                <span class="px-2 py-0.5 bg-gray-500/20 text-gray-400 rounded border border-gray-500/30 font-bold text-[9px]">
                    {{ strtoupper($log->risk_level) }}
                </span>
            @endif
        </td>
        
        <td class="px-6 py-4 text-center">
            @if($log->action_taken == 'Blocked' || $log->action_taken == 'Dropped')
                <span class="text-red-500 font-bold underline underline-offset-4 tracking-tighter">{{ $log->action_taken }}</span>
            @else
                <span class="text-green-500 font-bold tracking-tighter">{{ $log->action_taken }}</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-10 text-center text-gray-600 italic">
            Menunggu trafik jaringan masuk...
        </td>
    </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center">
                <div class="p-3 bg-cyan-500/10 rounded-xl mr-4">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Global Status</p>
                    <p class="text-sm text-gray-300 font-medium italic">Sistem dalam mode penahanan otomatis (Prevention Mode).</p>
                </div>
            </div>
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center justify-between">
                <p class="text-xs text-gray-400">Memerlukan tindakan manual?</p>
                <a href="{{ route('adminjaringan.action') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-[10px] font-bold rounded-lg border border-gray-700 transition-all uppercase tracking-widest">
                    Buka Security Action
                </a>
            </div>
        </div>
    </div>
</x-app-layout>