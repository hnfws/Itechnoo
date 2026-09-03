@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' — ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head')
</head>
<body class="flex min-h-dvh flex-col bg-surface font-sans text-ink antialiased">
    <a
        href="#main"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white"
    >
        Lewati ke konten utama
    </a>

    @include('partials.header')

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')

    @if (session('submitted'))
        @include('partials.success-modal')
    @endif

    @stack('scripts')
</body>
</html>
