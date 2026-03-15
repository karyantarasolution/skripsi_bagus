<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Konfigurasi Sistem & Jaringan</h2>
            <p class="text-xs text-gray-400 mt-1">Atur parameter keamanan dan ambang batas deteksi</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('superadmin.setting.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-[#161821] p-8 rounded-2xl border border-gray-800 shadow-lg relative overflow-hidden mb-6">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-500/5 rounded-full blur-3xl"></div>
                
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    IDS Engine Control
                </h3>

                <div class="flex items-center justify-between p-4 bg-[#0b0c10] rounded-xl border border-gray-700">
                    <div>
                        <p class="text-sm font-bold text-gray-200">Sistem Deteksi Aktif</p>
                        <p class="text-xs text-gray-500">Matikan saklar ini untuk menghentikan pemindaian paket masuk secara realtime</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ids_status" value="active" class="sr-only peer" {{ ($config['ids_status'] ?? '') == 'active' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>

            <div class="bg-[#161821] p-8 rounded-2xl border border-gray-800 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-cyan-500/5 rounded-full blur-3xl"></div>
                
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    Parameter Keamanan Jaringan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2 text-cyan-400">Endpoint Server (IP)</label>
                        <input type="text" name="server_ip" value="{{ $config['server_ip'] ?? '' }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-white px-4 py-3 transition-colors font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-2 text-cyan-400">Rate Limit Threshold (ms)</label>
                        <input type="number" name="max_request" value="{{ $config['max_request'] ?? '' }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-white px-4 py-3 transition-colors">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2 text-cyan-400">IP Whitelist (Safe Zone)</label>
                    <textarea name="whitelist" rows="2" class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-cyan-400 font-mono text-xs px-4 py-3 transition-colors">{{ $config['whitelist'] ?? '' }}</textarea>
                    <p class="text-[10px] text-gray-500 mt-1 italic">* Pisahkan dengan koma (,) untuk memasukkan banyak IP</p>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-400 mb-2 text-cyan-400">Notifikasi Alert (Email IT)</label>
                    <input type="email" name="alert_email" value="{{ $config['alert_email'] ?? '' }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-white px-4 py-3 transition-colors">
                </div>

                <div class="pt-6 border-t border-gray-800 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all transform hover:scale-105 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Konfigurasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>