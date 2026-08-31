@props(['text' => null])

@if ($text)
    <aside aria-label="Pemberitahuan" class="overflow-hidden border-b border-amber-500/50 bg-amber-400">
        <div class="flex w-max animate-marquee py-2.5 hover:[animation-play-state:paused] motion-reduce:w-full motion-reduce:animate-none motion-reduce:justify-center">
            <p class="shrink-0 pr-24 text-sm font-medium text-amber-950">
                Pemberitahuan : {{ $text }}
            </p>
            <p class="shrink-0 pr-24 text-sm font-medium text-amber-950 motion-reduce:hidden" aria-hidden="true">
                Pemberitahuan : {{ $text }}
            </p>
        </div>
    </aside>
@endif
