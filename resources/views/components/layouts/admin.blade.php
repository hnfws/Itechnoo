@props(['title' => 'Dashboard'])

@php
    $navItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Laporan', 'route' => 'admin.reports'],
        ['label' => 'Artikel', 'route' => 'admin.articles'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — {{ config('app.name') }} Admin</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-dvh bg-surface-muted font-sans text-ink antialiased">
    {{-- Checkbox tersembunyi untuk buka/tutup sidebar di mobile (tanpa JavaScript) --}}
    <input type="checkbox" id="sidebar-toggle" class="peer sr-only">

    {{-- Lapisan gelap saat sidebar terbuka di mobile --}}
    <label for="sidebar-toggle" class="fixed inset-0 z-30 hidden bg-black/50 peer-checked:block lg:hidden"></label>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-line bg-surface p-4 transition-transform peer-checked:translate-x-0 lg:translate-x-0">
        <div class="mb-6 flex justify-center pt-2">
            <span class="grid size-16 place-items-center rounded-full bg-surface-muted text-sm font-semibold text-ink">Logo</span>
        </div>

        <p class="px-2 text-xs font-medium tracking-wide text-ink-muted uppercase">Menu</p>

        <nav class="mt-2 space-y-2" aria-label="Navigasi admin">
            @foreach ($navItems as $item)
                @php $isActive = $item['route'] && request()->routeIs($item['route']); @endphp

                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    @if ($isActive) aria-current="page" @endif
                    @class([
                        'block rounded-xl px-4 py-2.5 text-sm font-medium transition',
                        'bg-brand-600 text-white' => $isActive,
                        'bg-brand-50 text-ink hover:bg-brand-100' => ! $isActive,
                    ])
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full rounded-xl bg-surface-muted px-4 py-2.5 text-left text-sm font-semibold text-ink transition hover:bg-line">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Area utama --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-line bg-surface px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <label for="sidebar-toggle" class="grid size-10 cursor-pointer place-items-center rounded-lg text-ink-muted transition hover:bg-surface-muted lg:hidden">
                    <span class="sr-only">Buka menu</span>
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                    </svg>
                </label>
                <h1 class="font-semibold text-ink">{{ $title }}</h1>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" class="relative grid size-10 place-items-center rounded-full text-ink-muted transition hover:bg-surface-muted" aria-label="Notifikasi">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13.7 21a2 2 0 01-3.4 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="absolute top-2 right-2 size-2 rounded-full bg-danger"></span>
                </button>

                <button type="button" class="flex items-center gap-2 rounded-full border border-line py-1 pr-3 pl-1 text-sm font-medium transition hover:bg-surface-muted">
                    <span class="grid size-7 place-items-center rounded-full bg-brand-600 text-xs font-bold text-white">A</span>
                    Profile
                </button>
            </div>
        </header>

        <main class="p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
