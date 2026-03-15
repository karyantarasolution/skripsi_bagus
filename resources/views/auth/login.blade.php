<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - IDS RSUD Ansari Saleh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white min-h-screen flex">
    
    <div class="hidden lg:flex w-1/2 bg-blue-600 justify-center items-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        
        <div class="relative z-10 text-center text-white px-12">
            <h1 class="text-4xl font-bold mb-2 tracking-wide">RSUD Dr. H. Moch. Ansari Saleh</h1>
            <h2 class="text-2xl font-semibold mb-6 text-blue-200">Intrusion Detection System (IDS)</h2>
            <p class="text-blue-100 text-lg mb-8 max-w-md mx-auto">Sistem keamanan jaringan defensif berbasis Signature-Based Detection dan Rate Limiting untuk perlindungan infrastruktur medis.</p>
            <div class="flex justify-center space-x-4 opacity-75">
                <div class="w-16 h-1 bg-white rounded-full"></div>
                <div class="w-4 h-1 bg-white rounded-full"></div>
                <div class="w-4 h-1 bg-white rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-8 sm:px-16 xl:px-24">
        
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-10">
                <img src="{{ asset('images/logo.png') }}" alt="Logo RS" class="w-20 h-20 mb-6 mx-auto lg:mx-0 object-contain p-1 border border-gray-200 rounded-xl shadow-sm">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="mt-2 text-sm text-gray-500">Silakan masuk ke akun Dashboard Security Anda</p>
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" placeholder="admin@rs.com">
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-2 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" placeholder="••••••••">
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-2 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer transition duration-150 ease-in-out">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                            Ingat saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                Lupa kata sandi?
                            </a>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 ease-in-out">
                        Masuk
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center lg:text-left">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} IDS RSUD Ansari Saleh. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>