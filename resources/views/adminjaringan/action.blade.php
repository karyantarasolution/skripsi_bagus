<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Network Security Action</h2>
            <p class="text-xs text-gray-400 mt-1">Eksekusi perintah keamanan manual terhadap host jaringan</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        @if(session('success'))
            <div class="bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 px-4 py-3 rounded-xl mb-6 flex items-center shadow-[0_0_15px_rgba(6,182,212,0.2)] font-bold text-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <form action="{{ route('adminjaringan.action.process') }}" method="POST" class="bg-[#161821] p-8 rounded-2xl border border-gray-800 shadow-lg relative overflow-hidden">
                    @csrf
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-500/5 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Manual Intervention
                    </h3>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Target IP Address</label>
                            <input type="text" name="ip_address" placeholder="Contoh: 192.168.1.100" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-xl text-white px-4 py-3 font-mono transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Pilih Tindakan</label>
                            <select name="action_type" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-red-500 rounded-xl text-white px-4 py-3 appearance-none transition-all">
                                <option value="block">BAN IP (Blokir Permanen)</option>
                                <option value="whitelist">WHITELIST (Abaikan Deteksi)</option>
                                <option value="drop">DROP CONNECTION (Putuskan Sesi)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Alasan / Justifikasi</label>
                            <textarea name="reason" rows="3" placeholder="Contoh: Terdeteksi melakukan scanning port berulang..." required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-red-500 rounded-xl text-white px-4 py-3 text-sm transition-all"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white font-black rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.3)] transition-all transform hover:scale-[1.02] active:scale-95 uppercase tracking-widest text-xs">
                                Eksekusi Perintah
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 shadow-lg">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Ancaman Terbaru</h4>
                    <div class="space-y-4">
                        @forelse($recentAttacks as $attack)
                        <div class="p-3 bg-[#0b0c10] rounded-xl border border-gray-700/50">
                            <p class="text-[10px] font-mono text-cyan-400 font-bold">{{ $attack->ip_address }}</p>
                            <p class="text-[9px] text-gray-500 mt-1 uppercase tracking-tighter">{{ $attack->kategori }}</p>
                        </div>
                        @empty
                        <p class="text-[10px] text-gray-600 italic uppercase">Tidak ada ancaman terdeteksi.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-blue-500/5 p-6 rounded-2xl border border-blue-500/20">
                    <h4 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-2 italic">Pro-Tip Admin</h4>
                    <p class="text-[10px] text-gray-400 leading-relaxed">Gunakan fitur **Whitelist** untuk IP internal instansi agar operasional RS Ansari Saleh tidak terganggu oleh False Positive.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>