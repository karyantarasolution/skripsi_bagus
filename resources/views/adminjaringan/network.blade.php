<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Pemetaan Jaringan Lokal</h2>
                <p class="text-xs text-gray-400 mt-1">Deteksi perangkat aktif pada subnet jaringan</p>
            </div>
            <button onclick="rescanNetwork()" id="scanBtn" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold rounded-xl hover:from-cyan-500 hover:to-blue-500 transition-all flex items-center shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Scan Ulang
            </button>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">
        <!-- Info Jaringan -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="networkStats">
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">IP Lokal</p>
                <h3 class="text-lg font-bold text-cyan-400 mt-2 font-mono">{{ $networkInfo['local_ip'] }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">Subnet</p>
                <h3 class="text-lg font-bold text-white mt-2 font-mono">{{ $networkInfo['subnet'] }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">Gateway</p>
                <h3 class="text-lg font-bold text-white mt-2 font-mono">{{ $networkInfo['gateway'] }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-green-500">
                <p class="text-xs text-gray-500 uppercase font-black">Total Perangkat</p>
                <h3 class="text-3xl font-bold text-green-400 mt-2" id="totalDevices">{{ count($devices) }}</h3>
            </div>
        </div>

        <!-- Tabel Perangkat -->
        <div class="bg-[#0b0c10] rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
            <div class="bg-[#161821] px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-300 flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                    Perangkat Terdeteksi
                </h3>
                <span class="text-[10px] font-mono text-gray-500" id="scanTime">Scan: {{ $networkInfo['scan_time'] }}</span>
            </div>

            <div class="overflow-x-auto" id="deviceTable">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 bg-[#0b0c10] tracking-widest border-b border-gray-800">
                            <th class="px-6 py-4 font-bold">No</th>
                            <th class="px-6 py-4 font-bold">IP Address</th>
                            <th class="px-6 py-4 font-bold">MAC Address</th>
                            <th class="px-6 py-4 font-bold">Hostname</th>
                            <th class="px-6 py-4 font-bold">Tipe/Vendor</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-mono" id="deviceBody">
                        @forelse($devices as $i => $device)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/20 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 text-cyan-400 font-bold">{{ $device['ip'] }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $device['mac'] }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $device['hostname'] ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 bg-purple-500/10 text-purple-400 rounded border border-purple-500/20 font-bold text-[9px]">
                                    {{ $device['type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($device['status'] === 'active')
                                    <span class="px-2 py-0.5 bg-green-500/20 text-green-500 rounded border border-green-500/30 font-bold text-[9px]">AKTIF</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-500/20 text-gray-400 rounded border border-gray-500/30 font-bold text-[9px]">INAKTIF</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-600 italic">
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

        <!-- Info -->
        <div class="bg-blue-500/5 p-6 rounded-2xl border border-blue-500/20">
            <h4 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-2 italic">Tentang Pemetaan Jaringan</h4>
            <p class="text-[10px] text-gray-400 leading-relaxed">Sistem mendeteksi perangkat aktif menggunakan ARP table dari router/gateway. Data perangkat di-cache selama 30 detik untuk performa. Perangkat diklasifikasikan berdasarkan MAC address vendor OUI.</p>
        </div>
    </div>

    <script>
        function rescanNetwork() {
            const btn = document.getElementById('scanBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor"></path></svg> Memindai...';

            fetch('{{ route("adminjaringan.network.scan.ajax") }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('totalDevices').textContent = data.total_devices;
                    document.getElementById('scanTime').textContent = 'Scan: ' + data.network_info.scan_time;

                    let html = '';
                    if (data.devices.length === 0) {
                        html = '<tr><td colspan="6" class="px-6 py-12 text-center text-gray-600 italic">Tidak ada perangkat ditemukan di jaringan ini.</td></tr>';
                    } else {
                        data.devices.forEach((device, i) => {
                            const statusClass = device.status === 'active' ? 'bg-green-500/20 text-green-500 border-green-500/30' : 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                            const statusText = device.status === 'active' ? 'AKTIF' : 'INAKTIF';
                            html += `<tr class="border-b border-gray-800/50 hover:bg-gray-800/20 transition-colors">
                                <td class="px-6 py-4 text-gray-500">${i + 1}</td>
                                <td class="px-6 py-4 text-cyan-400 font-bold">${device.ip}</td>
                                <td class="px-6 py-4 text-gray-300">${device.mac}</td>
                                <td class="px-6 py-4 text-gray-400">${device.hostname || '-'}</td>
                                <td class="px-6 py-4"><span class="px-2 py-0.5 bg-purple-500/10 text-purple-400 rounded border border-purple-500/20 font-bold text-[9px]">${device.type}</span></td>
                                <td class="px-6 py-4 text-center"><span class="px-2 py-0.5 ${statusClass} rounded border font-bold text-[9px]">${statusText}</span></td>
                            </tr>`;
                        });
                    }
                    document.getElementById('deviceBody').innerHTML = html;
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Scan Ulang';
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Scan Ulang';
                });
        }
    </script>
</x-app-layout>
