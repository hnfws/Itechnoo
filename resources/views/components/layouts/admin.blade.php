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

    <title>{{ $title }} - {{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        .admin-site-header {
            position: sticky !important;
            top: 0 !important;
            z-index: 10000 !important;
            isolation: isolate;
            background: var(--color-surface) !important;
        }

        .admin-sidebar {
            position: fixed !important;
            top: 4rem !important;
            bottom: 0 !important;
            left: 0 !important;
            z-index: 40 !important;
            width: 16rem;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 200ms ease;
        }

        #sidebar-toggle:checked ~ .admin-sidebar {
            transform: translateX(0);
        }

        .admin-sidebar-overlay {
            position: fixed;
            top: 4rem;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 30;
            display: none;
            background: rgba(15, 23, 42, 0.5);
        }

        #sidebar-toggle:checked ~ .admin-sidebar-overlay {
            display: block;
        }

        @media (min-width: 1024px) {
            .admin-sidebar {
                top: 0 !important;
                transform: translateX(0);
            }

            .admin-sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>
<body class="min-h-dvh bg-surface-muted font-sans text-ink antialiased">
    {{-- Checkbox tersembunyi untuk buka/tutup sidebar di mobile (tanpa JavaScript) --}}
    <input type="checkbox" id="sidebar-toggle" class="peer sr-only">

    {{-- Lapisan gelap saat sidebar terbuka di mobile --}}
    <label for="sidebar-toggle" class="admin-sidebar-overlay"></label>

    {{-- Sidebar --}}
    <aside class="admin-sidebar border-r border-line bg-surface p-4">
        <div class="mb-6 flex justify-center pt-2">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="size-16 object-contain">
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
                <button type="submit" class="block w-full rounded-xl bg-danger px-4 py-2.5 text-left text-sm font-semibold text-white transition hover:bg-brand-800">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Area utama --}}
    <div class="lg:pl-64">
        <header class="admin-site-header flex h-16 items-center justify-between gap-4 border-b border-line px-4 sm:px-6">
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
                <button type="button" class="flex items-center gap-2 rounded-full border border-line py-1 pr-3 pl-1 text-sm font-medium transition hover:bg-surface-muted">
                    <span class="grid size-7 place-items-center rounded-full bg-brand-600 text-xs font-bold text-white">A</span>
                    Profile
                </button>
            </div>
        </header>

        <main class="min-w-0 overflow-x-visible p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
