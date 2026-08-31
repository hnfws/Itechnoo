@props([
    'label',
    'value' => null,
    'accent' => 'default',
])

@php
    $valueColor = [
        'default' => 'text-ink',
        'high' => 'text-danger',
        'medium' => 'text-amber-500',
        'low' => 'text-green-600',
    ][$accent] ?? 'text-ink';
@endphp

<div {{ $attributes->class('flex flex-col justify-between rounded-card border border-line bg-surface p-6') }}>
    <p class="text-sm font-medium text-ink-muted">{{ $label }}</p>

    @if ($value !== null)
        <p class="mt-4 text-4xl font-bold tabular-nums {{ $valueColor }}">{{ $value }}</p>
    @endif

    {{ $slot }}
</div>
