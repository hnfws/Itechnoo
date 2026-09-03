@props(['report'])

@php
    // Akses data dengan aman baik sebagai Array maupun Eloquent Model
    $id = is_array($report) ? $report['id'] : $report->id;
    $title = is_array($report) ? $report['title'] : $report->title;
    $location = is_array($report) ? $report['location'] : $report->location;
    $description = is_array($report) ? $report['description'] : $report->description;
    $upvotes = is_array($report) ? ($report['upvotes'] ?? 0) : ($report->upvotes ?? 0);
    $lat = is_array($report) ? ($report['latitude'] ?? null) : $report->latitude;
    $lng = is_array($report) ? ($report['longitude'] ?? null) : $report->longitude;
@endphp

<article class="relative flex flex-col gap-4 rounded-card border border-line bg-surface p-4 transition hover:border-brand-300 hover:shadow-sm sm:flex-row sm:items-start sm:gap-5">
    
    {{-- Container Mini Peta --}}
    <div class="relative z-0 h-32 w-full shrink-0 overflow-hidden rounded-lg border border-line sm:size-24">
        <div id="card-map-{{ $id }}" class="h-full w-full"></div>
    </div>

    <div class="min-w-0 flex-1 space-y-1">
        <h3 class="font-semibold text-ink">
            <span class="text-ink-muted">Judul Laporan :</span>
            <a href="{{ route('reports.show', $id) }}" class="after:absolute after:inset-0 after:rounded-card focus-visible:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-brand-600">
                {{ $title }}
            </a>
        </h3>

        <p class="text-sm text-ink">
            <span class="text-ink-muted">Lokasi :</span> {{ $location }}
        </p>

        <p class="text-sm text-ink-muted">Deskripsi :</p>

        <p class="text-sm leading-relaxed text-ink-muted">&ldquo;{{ $description }}&rdquo;</p>
    </div>

    <div class="relative z-10 shrink-0 sm:w-20">
        <button
            type="button"
            class="flex w-full flex-col items-center gap-0.5 rounded-lg border border-line px-3 py-2 text-ink-muted transition hover:border-brand-400 hover:bg-brand-50 hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            aria-label="Upvote laporan {{ $title }}"
        >
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-sm font-semibold tabular-nums">{{ $upvotes }}</span>
            <span class="text-[11px] font-medium">Upvote</span>
        </button>
    </div>
</article>

{{-- Inisialisasi Leaflet untuk setiap card --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = {{ $lat ?? -6.200000 }};
        var lng = {{ $lng ?? 106.816666 }};
        var mapId = "card-map-{{ $id }}";

        // Inisialisasi peta mini
        var cardMap = L.map(mapId, {
            zoomControl: false,       // Sembunyikan tombol zoom +/- agar rapi
            dragging: false,          // Matikan drag agar tidak mengganggu scroll
            scrollWheelZoom: false,   // Matikan zoom scroll
            doubleClickZoom: false
        }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(cardMap);

        // Tambahkan pin marker
        L.marker([lat, lng]).addTo(cardMap);

        setTimeout(function() {
            cardMap.invalidateSize();
        }, 300);
    });
</script>