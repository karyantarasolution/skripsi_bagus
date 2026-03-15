<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RSUD Ansari Saleh') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0b0c10] text-gray-200 flex h-screen overflow-hidden selection:bg-purple-500 selection:text-white">
        
        @include('layouts.navigation')

        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            @isset($header)
                <header class="h-20 bg-[#12141c]/80 backdrop-blur-md border-b border-gray-800 flex items-center justify-between px-8 z-10">
                    {{ $header }}
                </header>
            @endisset

            <div class="flex-1 overflow-y-auto p-8 space-y-6">
                {{ $slot }}
            </div>
        </main>
        
    </body>
</html>