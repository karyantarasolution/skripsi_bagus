<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Overview Super Admin</h2>
            <p class="text-xs text-gray-400 mt-1">Sistem Deteksi Intrusi (IDS) & Rate Limiting</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <span class="block text-sm font-bold text-white">{{ Auth::user()->name ?? 'Kepala IT' }}</span>
                <span class="block text-xs text-cyan-400 font-medium">Super Admin</span>
            </div>
            <div class="h-10 w-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold shadow-[0_0_10px_rgba(6,182,212,0.5)]">
                {{ substr(Auth::user()->name ?? 'K', 0, 1) }}
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-400">Status Server IDS</p>
                <div class="p-2 bg-green-500/20 text-green-400 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-white">Online</h3>
            <p class="text-xs text-green-400 mt-2 flex items-center"><span class="w-2 h-2 rounded-full bg-green-400 animate-pulse mr-2"></span> Middleware aktif</p>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-400">Total Rule Signature</p>
                <div class="p-2 bg-purple-500/20 text-purple-400 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-white">{{ $totalRules ?? 0 }}</h3>
            <p class="text-xs text-purple-400 mt-2">Pola serangan dikenali</p>
            <a href="{{ route('superadmin.rules') }}" class="absolute bottom-4 right-6 text-xs text-purple-400 hover:text-white transition-colors">Kelola &rarr;</a>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-pink-500/10 rounded-full blur-2xl group-hover:bg-pink-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-400">Serangan Terblokir</p>
                <div class="p-2 bg-pink-500/20 text-pink-400 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-white">{{ number_format($totalBlocked ?? 0) }}</h3>
            <p class="text-xs text-pink-400 mt-2">Total anomali bulan ini</p>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-400">Staf Keamanan</p>
                <div class="p-2 bg-cyan-500/20 text-cyan-400 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-white">{{ $activeStaff ?? 0 }} Aktif</h3>
            <p class="text-xs text-gray-400 mt-2">Memantau jaringan saat ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2 bg-[#161821] rounded-2xl border border-gray-800 p-6 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Traffic Jaringan & Deteksi IDS (7 Hari Terakhir)</h3>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded-full border border-green-500/30">Live</span>
            </div>
            <div class="w-full h-72 bg-[#0b0c10] rounded-xl border border-gray-800 p-4 relative overflow-hidden">
                <canvas id="idsBarChart"></canvas>
            </div>
        </div>

        <div class="bg-[#161821] rounded-2xl border border-gray-800 p-6 shadow-lg">
            <h3 class="text-lg font-bold text-white mb-6">Status Router Terpantau</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 bg-cyan-400 rounded-full mr-3 shadow-[0_0_8px_rgba(34,211,238,0.8)]"></div>
                        <span class="text-sm font-medium text-gray-300">Router IGD (192.168.1.1)</span>
                    </div>
                    <span class="text-xs text-cyan-400 bg-cyan-400/10 px-2 py-1 rounded">Aman</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-[#0b0c10] rounded-xl border border-gray-800 hover:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 bg-cyan-400 rounded-full mr-3 shadow-[0_0_8px_rgba(34,211,238,0.8)]"></div>
                        <span class="text-sm font-medium text-gray-300">Router Farmasi (192.168.2.1)</span>
                    </div>
                    <span class="text-xs text-cyan-400 bg-cyan-400/10 px-2 py-1 rounded">Aman</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-[#1a0f14] rounded-xl border border-pink-500/30">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 bg-pink-500 rounded-full mr-3 animate-pulse shadow-[0_0_8px_rgba(236,72,153,0.8)]"></div>
                        <span class="text-sm font-medium text-pink-400">Router Server (10.0.0.1)</span>
                    </div>
                    <span class="text-xs font-bold text-pink-500 bg-pink-500/10 px-2 py-1 rounded">Warning</span>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('superadmin.setting') ?? '#' }}" class="w-full block text-center py-3 px-4 bg-[#0b0c10] border border-gray-700 text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-800 hover:text-white transition-all">
                    Kelola Pengaturan
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('idsBarChart').getContext('2d');
            
            const labels = {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!};
            const data = {!! json_encode($chartData ?? [12, 19, 3, 5, 2, 3, 15]) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Serangan Diblokir',
                        data: data,
                        backgroundColor: 'rgba(236, 72, 153, 0.8)',
                        borderColor: '#ec4899',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>