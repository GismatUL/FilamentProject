<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Reports' }} — {{ config('app.name') }}</title>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased">

<nav class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="/admin" class="text-sm text-gray-500 hover:text-gray-700">← Admin Panel</a>
        <span class="text-gray-300">|</span>
        <span class="font-semibold text-gray-800">Reports</span>
    </div>
    <span class="text-xs text-gray-400">Reports Module · nWidart/laravel-modules</span>
</nav>

<main class="max-w-6xl mx-auto px-6 py-8">
    @yield('content')
</main>

@livewireScripts
</body>
</html>
