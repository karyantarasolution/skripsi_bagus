<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Edit Rule Signature</h2>
            <p class="text-xs text-gray-400 mt-1">Perbarui pola serangan atau deskripsi</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('superadmin.rules') }}" class="px-5 py-2.5 bg-[#0b0c10] border border-gray-700 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-[#161821] rounded-2xl border border-gray-800 p-8 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <form action="{{ route('superadmin.rules.update', $rule->id) }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Kategori Ancaman</label>
                <select name="kategori" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-white px-4 py-3 transition-colors">
                    <option value="SQL Injection" {{ (old('kategori', $rule->kategori) == 'SQL Injection') ? 'selected' : '' }}>SQL Injection</option>
                    <option value="XSS" {{ (old('kategori', $rule->kategori) == 'XSS') ? 'selected' : '' }}>Cross-Site Scripting (XSS)</option>
                    <option value="Path Traversal" {{ (old('kategori', $rule->kategori) == 'Path Traversal') ? 'selected' : '' }}>Path Traversal / LFI</option>
                    <option value="Brute Force" {{ (old('kategori', $rule->kategori) == 'Brute Force') ? 'selected' : '' }}>Brute Force / Bad Bots</option>
                    <option value="Lainnya" {{ (old('kategori', $rule->kategori) == 'Lainnya') ? 'selected' : '' }}>Lainnya / Kustom</option>
                </select>
                @error('kategori') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Pola String / Keyword Berbahaya</label>
                <input type="text" name="pola" value="{{ old('pola', $rule->pola) }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-pink-400 font-mono px-4 py-3 transition-colors">
                @error('pola') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Deskripsi Rule (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-[#0b0c10] border border-gray-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded-xl text-white px-4 py-3 transition-colors">{{ old('deskripsi', $rule->deskripsi) }}</textarea>
                @error('deskripsi') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-gray-800">
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>