<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Arsip Log Intrusi</h2>
                <p class="text-xs text-gray-400 mt-1">Data rekaman serangan yang terdeteksi oleh sistem</p>
            </div>
            <a href="{{ route('adminjaringan.laporan') }}" class="px-4 py-2 bg-gray-800 border border-gray-700 text-white text-xs font-bold rounded-xl hover:bg-gray-700 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Ekspor Laporan
            </a>
        </div>
    </x-slot>

    <div class="mt-6 bg-[#161821] rounded-2xl border border-gray-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#0b0c10] border-b border-gray-800 text-[10px] uppercase text-gray-500 tracking-widest font-bold">
                        <th class="px-6 py-5">Waktu</th>
                        <th class="px-6 py-5">Alamat IP</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Endpoint</th>
                        <th class="px-6 py-5">Resiko</th>
                        <th class="px-6 py-5">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-mono">
                    @forelse($logs as $log)
                    <tr class="border-b border-gray-800/50 hover:bg-[#1a1c26] transition-colors">
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4 text-cyan-400 font-bold">{{ $log->ip_address }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $log->kategori }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $log->endpoint }}</td>
                        <td class="px-6 py-4">
                            @if($log->risk_level == 'Critical' || $log->risk_level == 'High')
                                <span class="text-red-500 font-bold tracking-tighter">{{ strtoupper($log->risk_level) }}</span>
                            @else
                                <span class="text-yellow-500 tracking-tighter">{{ strtoupper($log->risk_level) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $log->action_taken == 'Blocked' ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-green-500/10 text-green-500 border border-green-500/20' }}">
                                {{ $log->action_taken }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600 italic">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum ada log serangan yang masuk.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-[#0b0c10]/30 border-t border-gray-800">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>