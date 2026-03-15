<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white tracking-wide text-center md:text-left">SOC Security Dashboard</h2>
    </x-slot>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Logic Pie Chart
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

        // 2. Logic Bar Chart
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
    </script>
</x-app-layout>