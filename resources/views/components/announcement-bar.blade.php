@props(['reports' => []])

@php($reports = collect($reports))

@if ($reports->isNotEmpty())
    <aside aria-label="Pemberitahuan" class="overflow-hidden border-b border-amber-500/50 bg-amber-400">
        <div class="flex w-max animate-marquee py-2.5 hover:[animation-play-state:paused] motion-reduce:w-full motion-reduce:animate-none motion-reduce:justify-center">
            @foreach ($reports as $report)
                <p class="shrink-0 pr-24 text-sm font-medium text-amber-950">
                    Pemberitahuan: Sedang ada perbaikan di {{ $report->location ?: 'lokasi laporan' }}. Mohon maaf apabila perbaikan mengganggu perjalanan Anda. Harap gunakan jalan alternatif lain, terima kasih.
                </p>
            @endforeach

            <div aria-hidden="true" class="flex shrink-0">
                @foreach ($reports as $report)
                    <p class="shrink-0 pr-24 text-sm font-medium text-amber-950">
                        Pemberitahuan: Sedang ada perbaikan di {{ $report->location ?: 'lokasi laporan' }}. Mohon maaf apabila perbaikan mengganggu perjalanan Anda. Harap gunakan jalan alternatif lain, terima kasih.
                    </p>
                @endforeach
            </div>
        </div>
    </aside>
@endif
