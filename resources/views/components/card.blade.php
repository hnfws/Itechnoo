@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->class('rounded-card border border-line bg-surface p-6 shadow-sm') }}>
    @if ($title || $subtitle)
        <div class="mb-4 space-y-1">
            @if ($title)
                <h3 class="text-base font-semibold tracking-tight text-ink">{{ $title }}</h3>
            @endif

            @if ($subtitle)
                <p class="text-sm text-ink-muted">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
