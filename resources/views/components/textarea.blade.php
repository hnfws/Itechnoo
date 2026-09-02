@props([
    'name',
    'label' => null,
    'hint' => null,
    'rows' => 4,
])

@php
    $id = $attributes->get('id') ?? $name;
    $error = $errors->first($name);
    $describedBy = $error ? "{$id}-error" : ($hint ? "{$id}-hint" : null);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->except(['id'])->class([
            'block w-full rounded-lg border bg-surface px-3 py-2.5 text-sm text-ink transition placeholder:text-ink-muted focus:outline-2 focus:outline-offset-0',
            'border-line focus:outline-brand-600' => ! $error,
            'border-danger focus:outline-danger' => $error,
        ]) }}
    >{{ old($name, $slot) }}</textarea>

    @if ($error)
        <p id="{{ $id }}-error" class="text-sm text-danger">{{ $error }}</p>
    @elseif ($hint)
        <p id="{{ $id }}-hint" class="text-sm text-ink-muted">{{ $hint }}</p>
    @endif
</div>
