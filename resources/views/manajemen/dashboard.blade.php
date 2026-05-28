<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-tight">Executive Dashboard Monitoring</h2>
        <p class="text-xs text-gray-400">Ringkasan Keamanan Sistem Informasi RSUD Ansari Saleh</p>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-[#1e2235] to-[#161821] p-6 rounded-3xl border border-gray-800 shadow-2xl">
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black">Total Insiden</p>
                <h3 class="text-4xl font-bold text-white mt-2">{{ $total_serangan }}</h3>
                <p class="text-[10px] text-cyan-400 mt-2">Terdeteksi oleh IDS</p>
            </div>
            <div class="bg-gradient-to-br from-[#1e2235] to-[#161821] p-6 rounded-3xl border border-gray-800 shadow-2xl border-r-4 border-r-red-500">
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black">Ancaman Teratasi</p>
                <h3 class="text-4xl font-bold text-red-500 mt-2">{{ $total_blocked }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Status: Blocked / Dropped</p>
            </div>
            <div class="bg-gradient-to-br from-[#1e2235] to-[#161821] p-6 rounded-3xl border border-gray-800 shadow-2xl border-r-4 border-r-pink-500">
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black">Ancaman Critical</p>
                <h3 class="text-4xl font-bold text-pink-500 mt-2">{{ $total_critical }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Risk Level: Critical</p>
            </div>
            <div class="bg-gradient-to-br from-[#1e2235] to-[#161821] p-6 rounded-3xl border border-gray-800 shadow-2xl">
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black">Pengguna Sistem</p>
                <h3 class="text-4xl font-bold text-indigo-400 mt-2">{{ $total_user }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Personil Terdaftar</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-[#161821] p-8 rounded-3xl border border-gray-800 shadow-2xl">
                <h4 class="text-sm font-bold text-white mb-6 italic">Distribusi Jenis Serangan Jaringan</h4>
                <div class="max-w-md mx-auto">
                    <canvas id="manajemenChart"></canvas>
                </div>
            </div>

            <div class="bg-[#161821] p-8 rounded-3xl border border-gray-800 shadow-2xl">
                <h4 class="text-sm font-bold text-white mb-6 italic">Tren Serangan 7 Hari Terakhir</h4>
                <canvas id="trenChart" height="200"></canvas>
            </div>
        </div>

        <div class="bg-[#161821] p-6 rounded-3xl border border-gray-800 shadow-2xl">
            <h4 class="text-sm font-bold text-white mb-4">Akses Cepat Laporan</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('manajemen.laporan.cetak', 'all') }}" target="_blank" class="p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-blue-500/50 text-center transition-all">
                    <p class="text-xs font-bold text-blue-400">Seluruh Log</p>
                </a>
                <a href="{{ route('manajemen.laporan.cetak', 'critical') }}" target="_blank" class="p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-pink-500/50 text-center transition-all">
                    <p class="text-xs font-bold text-pink-400">Ancaman Critical</p>
                </a>
                <a href="{{ route('manajemen.laporan.cetak', 'today') }}" target="_blank" class="p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-cyan-500/50 text-center transition-all">
                    <p class="text-xs font-bold text-cyan-400">Hari Ini</p>
                </a>
                <a href="{{ route('manajemen.laporan') }}" class="p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-indigo-500/50 text-center transition-all">
                    <p class="text-xs font-bold text-indigo-400">Semua Laporan</p>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Doughnut Chart
        new Chart(document.getElementById('manajemenChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chart_kategori->pluck('kategori')) !!},
                datasets: [{
                    data: {!! json_encode($chart_kategori->pluck('total')) !!},
                    backgroundColor: ['#06b6d4', '#8b5cf6', '#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#9ca3af', padding: 20, font: { size: 11 } } }
                }
            }
        });

        // Bar Chart Tren
        new Chart(document.getElementById('trenChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($tren_mingguan->pluck('date')) !!},
                datasets: [{
                    label: 'Insiden',
                    data: {!! json_encode($tren_mingguan->pluck('total')) !!},
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6,
                    barPercentage: 0.5
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
