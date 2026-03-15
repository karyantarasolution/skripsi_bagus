<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Manajemen User</h2>
            <p class="text-xs text-gray-400 mt-1">Kelola akses akun staf IT dan manajemen RS</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('superadmin.users.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-sm font-bold rounded-xl transition-all shadow-[0_0_15px_rgba(168,85,247,0.4)] flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Akun
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-[#161821] rounded-2xl border border-gray-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs uppercase text-gray-500 border-b border-gray-800 bg-[#0b0c10]/50 tracking-wider">
                        <th class="px-6 py-5 font-bold">Nama Staf</th>
                        <th class="px-6 py-5 font-bold">Email</th>
                        <th class="px-6 py-5 font-bold">Hak Akses (Role)</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($users as $user)
                        <tr class="border-b border-gray-800 hover:bg-[#1a1c26] transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-200">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'super_admin')
                                    <span class="px-3 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-lg text-xs font-bold">Super Admin</span>
                                @elseif($user->role === 'admin_jaringan')
                                    <span class="px-3 py-1 bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 rounded-lg text-xs font-bold">Admin Jaringan</span>
                                @else
                                    <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-lg text-xs font-bold">Manajemen</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex justify-center space-x-3">
                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="p-2 bg-[#0b0c10] border border-cyan-500/30 hover:border-cyan-500 text-cyan-400 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-[#0b0c10] border border-pink-500/30 hover:border-pink-500 text-pink-500 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>