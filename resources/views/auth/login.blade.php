@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-400 bg-green-500/10 border border-green-500/30 px-4 py-3 rounded-xl">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">Alamat Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
            class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors placeholder-gray-500" placeholder="admin@rs.com">
        @error('email')
            <span class="text-pink-500 text-xs mt-2 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">Kata Sandi</label>
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors placeholder-gray-500" placeholder="••••••••">
        @error('password')
            <span class="text-pink-500 text-xs mt-2 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 bg-[#0b0c10] border-gray-700 rounded focus:ring-purple-500 text-purple-600 cursor-pointer">
            <label for="remember_me" class="ml-2 block text-sm text-gray-400 cursor-pointer select-none">
                Ingat saya
            </label>
        </div>

        @if (Route::has('password.request'))
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-cyan-400 hover:text-cyan-300 transition-colors">
                    Lupa kata sandi?
                </a>
            </div>
        @endif
    </div>

    <div>
        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all transform hover:scale-[1.02] active:scale-95 text-sm">
            Masuk
        </button>
    </div>
</form>

<div class="mt-6 pt-4 border-t border-gray-800 text-center">
    <p class="text-[11px] text-gray-500">
        &copy; {{ date('Y') }} IDS RSUD Dr. H. Moch. Ansari Saleh
    </p>
</div>
