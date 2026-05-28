<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-wide text-center md:text-left">SOC Security Dashboard</h2>
    </x-slot>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
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
</x-app-layout>
