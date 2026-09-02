@props(['report'])

<article class="relative flex flex-col gap-4 rounded-card border border-line bg-surface p-4 transition hover:border-brand-300 hover:shadow-sm sm:flex-row sm:items-start sm:gap-5">
    <div class="grid h-32 w-full shrink-0 place-items-center rounded-lg bg-brand-50 text-xs font-medium text-brand-700 sm:size-24">
        Map
    </div>

    <div class="min-w-0 flex-1 space-y-1">
        <h3 class="font-semibold text-ink">
            <span class="text-ink-muted">Judul Laporan :</span>
            <a href="{{ route('reports.show', $report['id']) }}" class="after:absolute after:inset-0 after:rounded-card focus-visible:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-brand-600">
                {{ $report['title'] }}
            </a>
        </h3>

        <p class="text-sm text-ink">
            <span class="text-ink-muted">Lokasi :</span> {{ $report['location'] }}
        </p>

        <p class="text-sm text-ink-muted">Deskripsi :</p>

        <p class="text-sm leading-relaxed text-ink-muted">&ldquo;{{ $report['description'] }}&rdquo;</p>
    </div>

    <div class="relative z-10 shrink-0 sm:w-20">
        <button
            type="button"
            class="flex w-full flex-col items-center gap-0.5 rounded-lg border border-line px-3 py-2 text-ink-muted transition hover:border-brand-400 hover:bg-brand-50 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            aria-label="Upvote laporan {{ $report['title'] }}"
        >
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-sm font-semibold tabular-nums">{{ $report['upvotes'] }}</span>
            <span class="text-[11px] font-medium">Upvote</span>
        </button>
    </div>
</article>
