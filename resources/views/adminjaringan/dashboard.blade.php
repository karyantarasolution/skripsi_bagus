<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-white tracking-wide text-center md:text-left">SOC Security Dashboard</h2>
            <div class="flex items-center gap-3">
                <button onclick="testAttack()" id="testBtn" class="px-4 py-2 bg-green-500/10 border border-green-500/30 text-green-400 text-[10px] font-bold rounded-xl hover:bg-green-500/20 transition-all uppercase tracking-widest flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Simulasi Serangan
                </button>
                <form action="{{ route('adminjaringan.reset.data') }}" method="POST" onsubmit="return confirm('Yakin ingin meresem SEMUA data attack log? Data lama akan terhapus permanen.');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500/10 border border-red-500/30 text-red-400 text-[10px] font-bold rounded-xl hover:bg-red-500/20 transition-all uppercase tracking-widest flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Reset Data
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mt-4 flex items-center shadow-[0_0_15px_rgba(34,197,94,0.2)] text-sm font-bold">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Box untuk test attack -->
    <div id="attackAlert" class="hidden mt-4 px-4 py-3 rounded-xl flex items-center text-sm font-bold shadow-lg"></div>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
            <p class="text-xs text-gray-500 uppercase font-black">Total Serangan</p>
            <h3 class="text-3xl font-bold text-white mt-2">{{ $totalSerangan }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-red-500">
            <p class="text-xs text-gray-500 uppercase font-black">Berhasil Diblokir</p>
            <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $totalBlocked }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
            <p class="text-xs text-gray-500 uppercase font-black">Security Rules</p>
            <h3 class="text-3xl font-bold text-cyan-400 mt-2">{{ $totalRules }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
            <p class="text-xs text-gray-500 uppercase font-black">Serangan Hari Ini</p>
            <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ $todayAttacks }}</h3>
        </div>
        <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-green-500">
            <p class="text-xs text-gray-500 uppercase font-black">IP Lokal</p>
            <h3 class="text-lg font-bold text-green-400 mt-2 font-mono">{{ $networkInfo['local_ip'] }}</h3>
            <p class="text-[10px] text-gray-500 font-mono">{{ $networkInfo['subnet'] }}/24</p>
        </div>
    </div>

    <!-- Grafik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="md:col-span-2 bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 italic">Tren Serangan (7 Hari Terakhir)</h4>
            <canvas id="trenChart" height="200"></canvas>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 italic">Kategori Ancaman</h4>
            <canvas id="kategoriChart"></canvas>
        </div>
    </div>

    <!-- Grafik Tambahan: Risk Level & Ancaman Terbaru -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 italic">Tingkat Keparahan</h4>
            <canvas id="riskChart"></canvas>
        </div>

        <div class="md:col-span-2 bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 flex items-center">
                <span class="w-2 h-2 bg-red-500 rounded-full mr-3 animate-pulse"></span>
                Ancaman Terbaru
            </h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 tracking-widest border-b border-gray-800">
                            <th class="pb-3 font-bold">Waktu</th>
                            <th class="pb-3 font-bold">IP</th>
                            <th class="pb-3 font-bold">Kategori</th>
                            <th class="pb-3 font-bold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-mono">
                        @forelse($recentAttacks as $attack)
                        <tr class="border-b border-gray-800/50">
                            <td class="py-3 text-gray-500">{{ $attack->created_at->format('H:i:s') }}</td>
                            <td class="py-3 text-cyan-400 font-bold">{{ $attack->ip_address }}</td>
                            <td class="py-3">
                                <span class="text-pink-500">{{ $attack->kategori }}</span>
                            </td>
                            <td class="py-3">
                                <span class="{{ $attack->action_taken == 'Blocked' ? 'text-red-500' : 'text-green-500' }}">{{ $attack->action_taken }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-6 text-center text-gray-600 italic">Belum ada data serangan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart - Kategori Ancaman
        const katLabels = {!! json_encode($kategoriData->pluck('kategori')) !!};
        const katValues = {!! json_encode($kategoriData->pluck('total')) !!};

        new Chart(document.getElementById('kategoriChart'), {
            type: 'doughnut',
            data: {
                labels: katLabels,
                datasets: [{
                    data: katValues,
                    backgroundColor: ['#ef4444', '#a855f7', '#6366f1', '#f59e0b', '#06b6d4'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 10 } } } }
            }
        });

        // Bar Chart - Tren 7 Hari
        const trenLabels = {!! json_encode($trenSerangan->pluck('date')) !!};
        const trenValues = {!! json_encode($trenSerangan->pluck('total')) !!};

        new Chart(document.getElementById('trenChart'), {
            type: 'bar',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Jumlah Deteksi',
                    data: trenValues,
                    backgroundColor: '#06b6d4',
                    borderRadius: 8,
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, grid: { color: '#1f2937' }, ticks: { color: '#9ca3af' } },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Doughnut Chart - Risk Level
        const riskLabels = {!! json_encode($riskDistribution->pluck('risk_level')) !!};
        const riskValues = {!! json_encode($riskDistribution->pluck('total')) !!};

        new Chart(document.getElementById('riskChart'), {
            type: 'doughnut',
            data: {
                labels: riskLabels,
                datasets: [{
                    data: riskValues,
                    backgroundColor: ['#22c55e', '#eab308', '#f97316', '#ef4444'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 10 } } }
                }
            }
        });
    </script>

    <script>
        function testAttack() {
            const btn = document.getElementById('testBtn');
            const alertBox = document.getElementById('attackAlert');
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-3.5 h-3.5 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor"></path></svg> Mengirim...';

            fetch('{{ route("adminjaringan.test.attack") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alertBox.className = 'mt-4 px-4 py-3 rounded-xl flex items-center text-sm font-bold shadow-lg bg-red-500/10 border border-red-500/30 text-red-400';
                    alertBox.innerHTML = '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>' + data.message + ' &mdash; <span class="font-mono text-cyan-400">' + data.data.kategori + '</span> | Pola: <span class="font-mono">' + data.data.pola + '</span> | Risk: <span class="text-yellow-400">' + data.data.risk + '</span> | Action: <span class="text-red-500">' + data.data.action + '</span>';
                } else {
                    alertBox.className = 'mt-4 px-4 py-3 rounded-xl flex items-center text-sm font-bold shadow-lg bg-yellow-500/10 border border-yellow-500/30 text-yellow-400';
                    alertBox.innerHTML = data.message;
                }
                alertBox.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Simulasi Serangan';

                setTimeout(() => { alertBox.classList.add('hidden'); }, 8000);

                setTimeout(() => location.reload(), 1500);
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Simulasi Serangan';
            });
        }
    </script>
</x-app-layout>
