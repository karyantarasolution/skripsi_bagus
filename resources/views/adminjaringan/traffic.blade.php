<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Live Traffic Monitoring</h2>
                <p class="text-xs text-gray-400 mt-1">Semua perangkat di jaringan lokal & serangan terdeteksi</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3 py-2 bg-green-500/10 border border-green-500/30 rounded-xl flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-green-500 text-[10px] font-black tracking-widest uppercase">IDS Active</span>
                </div>
                <div class="px-3 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-xl">
                    <span class="text-cyan-400 text-[10px] font-black tracking-widest uppercase" id="deviceCounter">0 Perangkat</span>
                </div>
                <div class="px-3 py-2 bg-red-500/10 border border-red-500/30 rounded-xl">
                    <span class="text-red-500 text-[10px] font-black tracking-widest uppercase" id="attackCounter">0 Serangan</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">
        <div class="bg-[#0b0c10] rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
            <div class="bg-[#161821] px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-300 flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-ping"></span>
                    Jaringan Aktif - Subnet {{ $networkInfo['subnet'] }}
                </h3>
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-mono text-gray-500">IP Lokal: {{ $networkInfo['local_ip'] }}</span>
                    <span class="text-[10px] font-mono text-gray-500" id="scanTime">Scan: {{ $networkInfo['scan_time'] }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 bg-[#0b0c10] tracking-widest border-b border-gray-800">
                            <th class="px-6 py-4 font-bold">Timestamp</th>
                            <th class="px-6 py-4 font-bold">Source IP</th>
                            <th class="px-6 py-4 font-bold">MAC Address</th>
                            <th class="px-6 py-4 font-bold">Target / Info</th>
                            <th class="px-6 py-4 font-bold">Detection Type</th>
                            <th class="px-6 py-4 font-bold">Severity</th>
                            <th class="px-6 py-4 font-bold text-center">System Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-mono" id="trafficBody">
                        @forelse($traffic as $row)
                        <tr class="border-b border-gray-800/50 {{ $row['type'] === 'attack' ? 'bg-red-500/5' : 'hover:bg-gray-800/20' }} transition-colors">
                            <td class="px-6 py-3 text-gray-500">{{ $row['time'] }}</td>
                            <td class="px-6 py-3 font-bold {{ $row['type'] === 'attack' ? 'text-red-400' : 'text-cyan-400' }}">{{ $row['ip_address'] }}</td>
                            <td class="px-6 py-3 text-gray-500 text-[10px]">{{ $row['mac'] }}</td>
                            <td class="px-6 py-3 text-gray-300">
                                @if($row['type'] === 'attack')
                                    <span class="text-pink-500 font-bold">{{ $row['endpoint'] }}</span>
                                @else
                                    <span class="text-gray-500">{{ $row['origin'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if($row['kategori'] === 'Normal Traffic')
                                    <span class="px-2 py-0.5 bg-green-500/10 text-green-500 rounded border border-green-500/20 font-bold text-[9px]">AMAN</span>
                                @else
                                    <span class="text-pink-500 font-bold">{{ $row['kategori'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if($row['risk_level'] === 'Critical' || $row['risk_level'] === 'High')
                                    <span class="px-2 py-0.5 bg-red-500/20 text-red-500 rounded border border-red-500/30 font-bold text-[9px]">{{ strtoupper($row['risk_level']) }}</span>
                                @elseif($row['risk_level'] === 'Medium')
                                    <span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-500 rounded border border-yellow-500/30 font-bold text-[9px]">{{ strtoupper($row['risk_level']) }}</span>
                                @elseif($row['risk_level'] === 'Low')
                                    <span class="px-2 py-0.5 bg-gray-500/20 text-gray-400 rounded border border-gray-500/30 font-bold text-[9px]">{{ strtoupper($row['risk_level']) }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-green-500/10 text-green-500 rounded border border-green-500/20 font-bold text-[9px]">SAFE</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($row['action_taken'] === 'Blocked' || $row['action_taken'] === 'Dropped')
                                    <span class="text-red-500 font-bold underline underline-offset-4 tracking-tighter">{{ $row['action_taken'] }}</span>
                                @elseif($row['action_taken'] === 'Logged')
                                    <span class="text-yellow-500 font-bold tracking-tighter">{{ $row['action_taken'] }}</span>
                                @else
                                    <span class="text-green-500 font-bold tracking-tighter">{{ $row['action_taken'] }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-600 italic">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Memindai jaringan...
                                </div>
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
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Auto Refresh</p>
                    <p class="text-sm text-gray-300 font-medium italic">Scan jaringan + deteksi serangan setiap 8 detik.</p>
                </div>
            </div>
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center justify-between">
                <p class="text-xs text-gray-400">Perlu tindakan manual?</p>
                <a href="{{ route('adminjaringan.action') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-[10px] font-bold rounded-lg border border-gray-700 transition-all uppercase tracking-widest">
                    Buka Security Action
                </a>
            </div>
        </div>
    </div>

    <script>
        let refreshInterval = setInterval(refreshTraffic, 8000);

        function refreshTraffic() {
            fetch('{{ route("adminjaringan.traffic.ajax") }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('deviceCounter').textContent = data.device_count + ' Perangkat';
                    document.getElementById('attackCounter').textContent = data.attack_count + ' Serangan';
                    document.getElementById('scanTime').textContent = 'Scan: ' + data.network_info.scan_time;

                    let html = '';
                    if (data.traffic.length === 0) {
                        html = '<tr><td colspan="7" class="px-6 py-12 text-center text-gray-600 italic">Memindai jaringan...</td></tr>';
                    } else {
                        data.traffic.forEach(row => {
                            const rowBg = row.type === 'attack' ? 'bg-red-500/5' : '';
                            const ipColor = row.type === 'attack' ? 'text-red-400' : 'text-cyan-400';
                            const targetHtml = row.type === 'attack'
                                ? '<span class="text-pink-500 font-bold">' + row.endpoint + '</span>'
                                : '<span class="text-gray-500">' + row.origin + '</span>';

                            let kategoriHtml = '';
                            if (row.kategori === 'Normal Traffic') {
                                kategoriHtml = '<span class="px-2 py-0.5 bg-green-500/10 text-green-500 rounded border border-green-500/20 font-bold text-[9px]">AMAN</span>';
                            } else {
                                kategoriHtml = '<span class="text-pink-500 font-bold">' + row.kategori + '</span>';
                            }

                            let riskHtml = '';
                            if (row.risk_level === 'Critical' || row.risk_level === 'High') {
                                riskHtml = '<span class="px-2 py-0.5 bg-red-500/20 text-red-500 rounded border border-red-500/30 font-bold text-[9px]">' + row.risk_level.toUpperCase() + '</span>';
                            } else if (row.risk_level === 'Medium') {
                                riskHtml = '<span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-500 rounded border border-yellow-500/30 font-bold text-[9px]">' + row.risk_level.toUpperCase() + '</span>';
                            } else if (row.risk_level === 'Low') {
                                riskHtml = '<span class="px-2 py-0.5 bg-gray-500/20 text-gray-400 rounded border border-gray-500/30 font-bold text-[9px]">' + row.risk_level.toUpperCase() + '</span>';
                            } else {
                                riskHtml = '<span class="px-2 py-0.5 bg-green-500/10 text-green-500 rounded border border-green-500/20 font-bold text-[9px]">SAFE</span>';
                            }

                            let actionClass = '';
                            if (row.action_taken === 'Blocked' || row.action_taken === 'Dropped') {
                                actionClass = 'text-red-500 font-bold underline underline-offset-4';
                            } else if (row.action_taken === 'Logged') {
                                actionClass = 'text-yellow-500 font-bold';
                            } else {
                                actionClass = 'text-green-500 font-bold';
                            }

                            html += '<tr class="border-b border-gray-800/50 ' + rowBg + ' transition-colors">' +
                                '<td class="px-6 py-3 text-gray-500">' + row.time + '</td>' +
                                '<td class="px-6 py-3 font-bold ' + ipColor + '">' + row.ip_address + '</td>' +
                                '<td class="px-6 py-3 text-gray-500 text-[10px]">' + (row.mac || '-') + '</td>' +
                                '<td class="px-6 py-3 text-gray-300">' + targetHtml + '</td>' +
                                '<td class="px-6 py-3">' + kategoriHtml + '</td>' +
                                '<td class="px-6 py-3">' + riskHtml + '</td>' +
                                '<td class="px-6 py-3 text-center"><span class="' + actionClass + ' tracking-tighter">' + row.action_taken + '</span></td>' +
                                '</tr>';
                        });
                    }
                    document.getElementById('trafficBody').innerHTML = html;
                })
                .catch(err => console.error('Refresh error:', err));
        }
    </script>
</x-app-layout>
