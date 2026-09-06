@php
    $navItems = [
        ['label' => 'Beranda', 'url' => '/'],
        ['label' => 'Laporan', 'url' => '/laporan'],
        ['label' => 'Artikel', 'url' => '/artikel'],
    ];
@endphp

<style>
    .site-header {
        position: relative;
        z-index: 1000;
        background: var(--color-surface);
    }

    @media (min-width: 1024px) {
        .site-header {
            position: sticky;
            top: 0;
        }
    }
</style>

<header class="site-header border-b border-line">
    <x-container class="flex h-16 items-center justify-between gap-4">
        <a href="/" class="flex shrink-0 items-center gap-2 font-semibold tracking-tight">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="size-9 object-contain">
            <span>{{ config('app.name') }}</span>
        </a>

        <nav class="hidden lg:block" aria-label="Navigasi utama">
            <ul class="flex items-center gap-1">
                @foreach ($navItems as $item)
                    @php $isActive = request()->is(trim($item['url'], '/') ?: '/'); @endphp

                    <li>
                        <a
                            href="{{ $item['url'] }}"
                            @if ($isActive) aria-current="page" @endif
                            @class([
                                'rounded-full px-4 py-2 text-sm font-medium transition',
                                'bg-brand-50 text-brand-700' => $isActive,
                                'text-ink-muted hover:bg-surface-muted hover:text-ink' => ! $isActive,
                            ])
                        >
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <button
            type="button"
            data-nav-toggle
            aria-expanded="false"
            aria-controls="mobile-nav"
            class="grid size-10 place-items-center rounded-lg text-ink-muted transition hover:bg-surface-muted hover:text-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 lg:hidden"
        >
            <span class="sr-only">Buka menu navigasi</span>
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
            </svg>
        </button>
    </x-container>

    <div id="mobile-nav" data-nav-panel hidden class="border-t border-line lg:hidden">
        <x-container class="space-y-1 py-3">
            @foreach ($navItems as $item)
                <a
                    href="{{ $item['url'] }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium text-ink-muted transition hover:bg-surface-muted hover:text-ink"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </x-container>
    </div>
</header>
