@props(['title'])

<div {{ $attributes->class('flex flex-wrap items-center justify-between gap-3') }}>
    <h2 class="text-lg font-semibold tracking-tight text-ink sm:text-xl">{{ $title }}</h2>

    @isset($action)
        <div class="shrink-0">
            {{ $action }}
        </div>
    @endisset
</div>
