<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Laporan Analitik Statistik Anomali</h2>
                <p class="text-xs text-gray-400 mt-1">Ringkasan frekuensi insiden keamanan berdasarkan kategori ancaman</p>
            </div>
            <a href="{{ route('adminjaringan.laporan.analitik.cetak') }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold rounded-xl hover:from-cyan-500 hover:to-blue-500 transition-all flex items-center shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF Analitik
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">
        <!-- Kartu Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">Total Insiden</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ $totalSerangan }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-pink-500">
                <p class="text-xs text-gray-500 uppercase font-black">Kategori Dominan</p>
                <h3 class="text-lg font-bold text-pink-500 mt-2">{{ $dominant->kategori ?? '-' }}</h3>
                <p class="text-[10px] text-gray-500">{{ $dominant->total ?? 0 }} insiden ({{ $dominant->persentase ?? 0 }}%)</p>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-red-500">
                <p class="text-xs text-gray-500 uppercase font-black">Critical & High</p>
                <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $trendRisk->whereIn('risk_level', ['Critical','High'])->sum('total') }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-cyan-500">
                <p class="text-xs text-gray-500 uppercase font-black">Low & Medium</p>
                <h3 class="text-3xl font-bold text-cyan-400 mt-2">{{ $trendRisk->whereIn('risk_level', ['Low','Medium'])->sum('total') }}</h3>
            </div>
        </div>

        <!-- Grafik Distribusi Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
                <h4 class="text-sm font-bold text-white mb-4 italic">Distribusi Kategori Ancaman</h4>
                <canvas id="kategoriPieChart" height="250"></canvas>
            </div>
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
                <h4 class="text-sm font-bold text-white mb-4 italic">Distribusi Tingkat Keparahan</h4>
                <canvas id="riskBarChart" height="250"></canvas>
            </div>
        </div>

        <!-- Tabel Detail Kategori -->
        <div class="bg-[#161821] rounded-2xl border border-gray-800 shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800">
                <h4 class="text-sm font-bold text-white">Detail Frekuensi Per Kategori Ancaman</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 bg-[#0b0c10] tracking-widest border-b border-gray-800">
                            <th class="px-6 py-4 font-bold">Kategori Ancaman</th>
                            <th class="px-6 py-4 font-bold text-center">Jumlah Insiden</th>
                            <th class="px-6 py-4 font-bold text-center">Persentase</th>
                            <th class="px-6 py-4 font-bold text-center">Tingkat Dominasi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($statKategori as $item)
                        <tr class="border-b border-gray-800/50 hover:bg-[#1a1c26] transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-200">{{ $item->kategori }}</td>
                            <td class="px-6 py-4 text-center text-cyan-400 font-bold">{{ $item->total }}</td>
                            <td class="px-6 py-4 text-center text-gray-300">{{ $item->persentase }}%</td>
                            <td class="px-6 py-4 text-center">
                                <div class="w-full bg-gray-700 rounded-full h-2.5 max-w-[200px] mx-auto">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2.5 rounded-full" style="width: {{ $item->persentase }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-600">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Tren Bulanan -->
        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 italic">Tren Serangan (7 Hari Terakhir)</h4>
            <canvas id="trenHarianChart" height="150"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart Kategori
        new Chart(document.getElementById('kategoriPieChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($statKategori->pluck('kategori')) !!},
                datasets: [{
                    data: {!! json_encode($statKategori->pluck('total')) !!},
                    backgroundColor: ['#ef4444', '#a855f7', '#6366f1', '#f59e0b', '#06b6d4'],
                    borderWidth: 2,
                    borderColor: '#161821'
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 11 }, padding: 15 } }
                }
            }
        });

        // Bar Chart Risk Level
        new Chart(document.getElementById('riskBarChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($trendRisk->pluck('risk_level')) !!},
                datasets: [{
                    label: 'Jumlah Insiden',
                    data: {!! json_encode($trendRisk->pluck('total')) !!},
                    backgroundColor: ['#22c55e', '#eab308', '#f97316', '#ef4444'],
                    borderRadius: 6,
                    barPercentage: 0.6
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

        // Line Chart Tren Harian
        const trenLabels = {!! json_encode($harian->pluck('date')) !!};
        const trenValues = {!! json_encode($harian->pluck('total')) !!};

        new Chart(document.getElementById('trenHarianChart'), {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Insiden',
                    data: trenValues,
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6,182,212,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#06b6d4',
                    pointRadius: 4
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
    </script>
</x-app-layout>
