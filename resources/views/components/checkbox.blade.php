@props([
    'name',
    'value' => 1,
])

@php
    $id = $attributes->get('id') ?? $name;
@endphp

<label for="{{ $id }}" class="flex cursor-pointer items-start gap-3">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked(old($name))
        {{ $attributes->except(['id'])->class('mt-0.5 size-4 shrink-0 rounded border-line text-brand-600 focus:ring-2 focus:ring-brand-600 focus:ring-offset-0') }}
    >
    <span class="text-sm text-ink-muted">{{ $slot }}</span>
</label>
