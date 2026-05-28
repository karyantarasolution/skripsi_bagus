<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white tracking-wide">Laporan Ketersediaan Infrastruktur</h2>
                <p class="text-xs text-gray-400 mt-1">Evaluasi performa sistem berdasarkan durasi operasional vs insiden keamanan</p>
            </div>
            <a href="{{ route('adminjaringan.laporan.ketersediaan.cetak') }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold rounded-xl hover:from-cyan-500 hover:to-blue-500 transition-all flex items-center shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">
        <!-- Kartu Metrik Ketersediaan -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">Total Masa Pantau</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ $totalHari }} Hari</h3>
                <p class="text-[10px] text-gray-500 mt-1">{{ $totalMonitoringHours }} jam monitoring</p>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-green-500">
                <p class="text-xs text-gray-500 uppercase font-black">Hari Aman (Normal)</p>
                <h3 class="text-3xl font-bold text-green-500 mt-2">{{ $hariAman }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg border-l-4 border-l-red-500">
                <p class="text-xs text-gray-500 uppercase font-black">Hari Terjadi Insiden</p>
                <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $hariTerkenaSerangan }}</h3>
            </div>
            <div class="bg-[#161821] p-5 rounded-2xl border border-gray-800 shadow-lg">
                <p class="text-xs text-gray-500 uppercase font-black">Rata-rata Serangan/Hari</p>
                <h3 class="text-3xl font-bold text-cyan-400 mt-2">{{ $avgPerHari }}</h3>
            </div>
        </div>

        <!-- Ketersediaan & Chart Musiman -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Indikator Ketersediaan -->
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg flex flex-col items-center justify-center">
                <h4 class="text-sm font-bold text-white mb-6 italic">Tingkat Ketersediaan Sistem</h4>
                <div class="relative w-48 h-48">
                    <canvas id="availabilityChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <span class="text-4xl font-black {{ $availabilityPercent >= 90 ? 'text-green-500' : ($availabilityPercent >= 70 ? 'text-yellow-500' : 'text-red-500') }}">{{ $availabilityPercent }}%</span>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">Available</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6 mt-4 text-xs">
                    <div class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Aman ({{ $hariAman }} hr)</div>
                    <div class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> Insiden ({{ $hariTerkenaSerangan }} hr)</div>
                </div>
            </div>

            <!-- Jam Sibuk Serangan -->
            <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
                <h4 class="text-sm font-bold text-white mb-4 italic">Distribusi Jam Serangan (24 Jam)</h4>
                <canvas id="jamChart" height="220"></canvas>
            </div>
        </div>

        <!-- Timeline Serangan Harian -->
        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
            <h4 class="text-sm font-bold text-white mb-4 italic">Timeline Frekuensi Serangan Harian</h4>
            <canvas id="harianChart" height="150"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Doughnut - Ketersediaan
        new Chart(document.getElementById('availabilityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Aman (Normal)', 'Terjadi Insiden'],
                datasets: [{
                    data: [{{ $hariAman }}, {{ $hariTerkenaSerangan }}],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                plugins: { legend: { display: false } }
            }
        });

        // Bar Chart - Distribusi Jam
        const jamLabels = {!! json_encode($jamSerangan->pluck('jam')) !!};
        const jamValues = {!! json_encode($jamSerangan->pluck('total')) !!};

        new Chart(document.getElementById('jamChart'), {
            type: 'bar',
            data: {
                labels: jamLabels.map(j => j + ':00'),
                datasets: [{
                    label: 'Serangan',
                    data: jamValues,
                    backgroundColor: '#f97316',
                    borderRadius: 4,
                    barPercentage: 0.7
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, grid: { color: '#1f2937' }, ticks: { color: '#9ca3af' } },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af', maxRotation: 0 } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Bar Chart - Timeline Harian
        const labels = {!! json_encode($harian->pluck('date')) !!};
        const values = {!! json_encode($harian->pluck('total')) !!};

        new Chart(document.getElementById('harianChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Serangan',
                    data: values,
                    backgroundColor: values.map(v => v > 5 ? '#ef4444' : '#22c55e'),
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
