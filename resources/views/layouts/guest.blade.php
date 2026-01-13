<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Edu World') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- REQUIRED FOR LIVEWIRE --}}
    @livewireStyles
</head>

<body class="min-h-screen font-sans antialiased">

@php
    $bgImage = asset('images/login-bg.jpg');
@endphp

<div class="relative min-h-screen overflow-hidden">

    <!-- Background Image -->
    <div
        class="absolute inset-0 bg-center bg-cover"
        style="background-image: url('{{ $bgImage }}');">
    </div>

    <!-- Blur -->
    <div class="absolute inset-0 backdrop-blur-sm"></div>

    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/30"></div>

    <!-- Page Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>

</div>

{{-- REQUIRED FOR LIVEWIRE --}}
@livewireScripts
</body>
</html>
