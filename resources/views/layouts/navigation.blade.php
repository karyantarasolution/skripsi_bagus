<aside class="w-64 bg-[#12141c] flex flex-col border-r border-gray-800 transition-all duration-300">
    <div class="h-20 flex items-center px-6 border-b border-gray-800">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 bg-white rounded-full p-0.5 mr-3 shadow-[0_0_10px_rgba(168,85,247,0.4)]">
        <div>
            <span class="text-sm font-extrabold block text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">IT Security SOC</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider">RS Ansari Saleh</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        
        @if(Auth::user()->role === 'super_admin')
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('superadmin.dashboard') ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a href="{{ route('superadmin.users') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('superadmin.users*') ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-semibold text-sm">Manajemen User</span>
            </a>
            <a href="{{ route('superadmin.rules') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('superadmin.rules') ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path></svg>
                <span class="font-semibold text-sm">Rule & Signature</span>
            </a>
            <a href="{{ route('superadmin.setting') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('superadmin.setting') ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-semibold text-sm">Setting Jaringan</span>
            </a>
        
        @elseif(Auth::user()->role === 'admin_jaringan')
            <a href="{{ route('adminjaringan.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('adminjaringan.dashboard') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a href="{{ route('adminjaringan.traffic') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('adminjaringan.traffic') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="font-semibold text-sm">Live Traffic</span>
            </a>
            <a href="{{ route('adminjaringan.log') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('adminjaringan.log') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="font-semibold text-sm">Log Intrusi</span>
            </a>
            <a href="{{ route('adminjaringan.action') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('adminjaringan.action') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="font-semibold text-sm">Blacklist / Whitelist</span>
            </a>
            <a href="{{ route('adminjaringan.laporan') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('adminjaringan.laporan') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span class="font-semibold text-sm">Cetak Laporan</span>
            </a>

        @elseif(Auth::user()->role === 'manajemen')
            <a href="{{ route('manajemen.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('manajemen.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a href="{{ route('manajemen.laporan') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('manajemen.laporan') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-[0_0_15px_rgba(99,102,241,0.4)]' : 'text-gray-400 hover:bg-[#1a1c26] hover:text-white' }} rounded-xl transition-all">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-semibold text-sm">Download Laporan PDF</span>
            </a>
        @endif

    </nav>

    <div class="p-4 border-t border-gray-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all text-sm font-bold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</aside>