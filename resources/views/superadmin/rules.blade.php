<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Manajemen Rule & Signature</h2>
            <p class="text-xs text-gray-400 mt-1">Kelola kamus pola serangan untuk deteksi otomatis</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('superadmin.rules.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-sm font-bold rounded-xl transition-all shadow-[0_0_15px_rgba(168,85,247,0.4)] flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Rule Baru
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl"></div>
            <div>
                <p class="text-sm font-medium text-gray-400">Total Rule Aktif</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalRules }}</h3>
            </div>
            <div class="p-4 bg-purple-500/10 text-purple-400 border border-purple-500/30 rounded-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-pink-500/10 rounded-full blur-2xl"></div>
            <div>
                <p class="text-sm font-medium text-gray-400">Database Engine</p>
                <h3 class="text-lg font-black text-white mt-1 font-mono">MySQL</h3>
                <p class="text-xs text-pink-400 mt-1">Sistem Deteksi Tersinkronisasi</p>
            </div>
            <div class="p-4 bg-pink-500/10 text-pink-400 border border-pink-500/30 rounded-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
        </div>

        <div class="bg-[#161821] p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl"></div>
            <div>
                <p class="text-sm font-medium text-gray-400">Status Filtering</p>
                <h3 class="text-2xl font-black text-cyan-400 mt-1">Berjalan</h3>
                <p class="text-xs text-gray-500 mt-1">Middleware aktif memantau</p>
            </div>
            <div class="p-4 bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 rounded-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-[#161821] rounded-2xl border border-gray-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs uppercase text-gray-500 border-b border-gray-800 bg-[#0b0c10]/50 tracking-wider">
                        <th class="px-6 py-5 font-bold">Kategori</th>
                        <th class="px-6 py-5 font-bold">Pola String / Regex</th>
                        <th class="px-6 py-5 font-bold">Deskripsi</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($rules as $rule)
                        <tr class="border-b border-gray-800 hover:bg-[#1a1c26] transition-colors">
                            <td class="px-6 py-4">
                                @if($rule->kategori == 'SQL Injection')
                                    <span class="px-3 py-1 bg-orange-500/10 text-orange-400 border border-orange-500/20 rounded-lg text-xs font-bold">{{ $rule->kategori }}</span>
                                @elseif($rule->kategori == 'XSS')
                                    <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg text-xs font-bold">{{ $rule->kategori }}</span>
                                @elseif($rule->kategori == 'Path Traversal')
                                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-lg text-xs font-bold">{{ $rule->kategori }}</span>
                                @else
                                    <span class="px-3 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-lg text-xs font-bold">{{ $rule->kategori }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-pink-400 font-bold">{{ $rule->pola }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $rule->deskripsi ?? '-' }}</td>
                            <td class="px-6 py-4 flex justify-center space-x-3">
                                <a href="{{ route('superadmin.rules.edit', $rule->id) }}" class="p-2 bg-[#0b0c10] border border-cyan-500/30 hover:border-cyan-500 text-cyan-400 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('superadmin.rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Hapus rule ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-[#0b0c10] border border-pink-500/30 hover:border-pink-500 text-pink-500 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada rule yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800 bg-[#12141c]">
            {{ $rules->links() }}
        </div>
    </div>
</x-app-layout>