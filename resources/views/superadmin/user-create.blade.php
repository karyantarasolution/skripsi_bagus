<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Tambah Akun Baru</h2>
            <p class="text-xs text-gray-400 mt-1">Buat kredensial akses untuk staf RS</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('superadmin.users') }}" class="px-5 py-2.5 bg-[#0b0c10] border border-gray-700 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-[#161821] rounded-2xl border border-gray-800 p-8 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors">
                @error('name') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors">
                @error('email') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Password Asli</label>
                <input type="password" name="password" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors">
                @error('password') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Hak Akses (Role)</label>
                <select name="role" required class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors">
                    <option value="">-- Pilih Role Akses --</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Kepala IT)</option>
                    <option value="admin_jaringan" {{ old('role') == 'admin_jaringan' ? 'selected' : '' }}>Admin Jaringan (Staf Security)</option>
                    <option value="manajemen" {{ old('role') == 'manajemen' ? 'selected' : '' }}>Manajemen (Direksi)</option>
                </select>
                @error('role') <span class="text-pink-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-gray-800">
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all">
                    Simpan Data Akun
                </button>
            </div>
        </form>
    </div>
</x-app-layout>