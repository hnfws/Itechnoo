@props(['report'])

@php
    // Akses data dengan aman baik sebagai Array maupun Eloquent Model
    $id = is_array($report) ? $report['id'] : $report->id;
    $title = is_array($report) ? $report['title'] : $report->title;
    $location = is_array($report) ? $report['location'] : $report->location;
    $description = is_array($report) ? $report['description'] : $report->description;
    $status = is_array($report) ? ($report['status'] ?? 'belum diverifikasi') : $report->status;
    $statusLabel = is_array($report)
        ? (\App\Models\Report::STATUSES[$status] ?? $status)
        : $report->status_label;
    $statusStyle = match ($status) {
        'terverifikasi' => 'border-green-200 bg-green-50 text-green-700',
        'terverifikasi_in_progress' => 'border-blue-200 bg-blue-50 text-blue-700',
        'resolved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'rejected' => 'border-red-200 bg-red-50 text-red-700',
        default => 'border-amber-200 bg-amber-50 text-amber-700',
    };
    $upvotes = is_array($report)
        ? ($report['upvotes'] ?? $report['upvotes_count'] ?? 0)
        : ($report->upvotes_count ?? $report->upvote_count ?? $report->upvotes()->count());
    $lat = is_array($report) ? ($report['latitude'] ?? null) : $report->latitude;
    $lng = is_array($report) ? ($report['longitude'] ?? null) : $report->longitude;
    $voterKey = request()->cookie('guest_reporter_key');
    $hasUpvoted = $voterKey && $report instanceof \App\Models\Report
        ? $report->upvotes()->where('voter_key', $voterKey)->exists()
        : false;

    $extremeWeather = is_array($report) ? ($report['extreme_weather'] ?? null) : ($report->extreme_weather ?? null);
@endphp

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.weather-checker').forEach(function(el) {
        var lat = el.getAttribute('data-lat');
        var lng = el.getAttribute('data-lng');

        if (!lat || !lng) return;

        // Tembak API Open-Meteo secara real-time
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=weather_code,wind_speed_10m`)
            .then(response => {
                if (!response.ok) throw new Error('Weather API request failed');
                return response.json();
            })
            .then(data => {
                if (data && data.current) {
                    var code = data.current.weather_code;
                    var wind = data.current.wind_speed_10m;

                    // Daftar WMO Weather Code untuk Cuaca Ekstrem:
                    // 65: Hujan Deras, 67: Hujan Es, 82: Hujan Badai, 95/96/99: Badai Petir
                    var extremeCodes = [65, 67, 75, 82, 95, 96, 99];

                    // Kriteria Ekstrem: Kode cuaca ekstrem ATAU Kecepatan Angin > 40 km/j
                    if (extremeCodes.includes(code) || wind > 40) {
                        el.innerHTML = `
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-600 border border-red-500/20">
                                ⚠️ Cuaca Ekstrem
                            </span>
                        `;
                    }
                }
            })
            .catch(err => console.error("Gagal memuat data cuaca real-time:", err));
    });
});
</script>

<article class="relative flex flex-col gap-4 rounded-card border border-line bg-surface p-4 transition hover:border-brand-300 hover:shadow-sm sm:flex-row sm:items-start sm:gap-5">
    
    {{-- Container Mini Peta (Tambahkan cursor-pointer & z-20 agar dapat diklik) --}}
    <div class="relative z-20 h-32 w-full shrink-0 overflow-hidden rounded-lg border border-line sm:size-24 cursor-pointer" title="Buka di Google Maps">
        <div id="card-map-{{ $id }}" class="h-full w-full"></div>
    </div>

    <div class="min-w-0 flex-1 space-y-1">
        <h3 class="flex flex-wrap items-center gap-2 font-semibold text-ink">
            <span class="text-ink-muted">Judul Laporan :</span>
            <a href="{{ route('reports.show', $id) }}" class="after:absolute after:inset-0 after:rounded-card focus-visible:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-brand-600">
                {{ $title }}
            </a>
            <span class="relative z-10 inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusStyle }}">
                Status: {{ $statusLabel }}
            </span>
            <span class="weather-checker relative z-10 inline-flex items-center"
                  data-lat="{{ $lat }}"
                  data-lng="{{ $lng }}"></span>
            @if (!empty($extremeWeather))
                <span class="relative z-10 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 animate-pulse">
                    {{ $extremeWeather }}
                </span>
            @endif
        </h3>

        <p class="text-sm text-ink">
            <span class="text-ink-muted">Lokasi :</span> {{ $location }}
        </p>

        <p class="text-sm text-ink-muted">Deskripsi :</p>

        <p class="text-sm leading-relaxed text-ink-muted">&ldquo;{{ $description }}&rdquo;</p>
    </div>

    <div class="relative z-20 shrink-0 sm:w-20">
        <form action="{{ route('reports.upvote', $id) }}" method="POST" onsubmit="submitUpvote(event, this)">
            @csrf
            <button
                type="submit"
                id="upvote-btn-{{ $id }}"
                class="flex w-full flex-col items-center gap-0.5 rounded-lg border px-3 py-2 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 {{ $hasUpvoted ? 'border-brand-400 bg-brand-50 text-brand-700' : 'border-line text-ink-muted hover:border-brand-400 hover:bg-brand-50 hover:text-brand-700' }}"
                aria-label="{{ $hasUpvoted ? 'Batalkan dukungan laporan' : 'Upvote laporan' }} {{ $title }}"
            >
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span id="upvote-count-{{ $id }}" data-upvote-count="{{ $id }}" class="text-sm font-semibold tabular-nums">{{ $upvotes }}</span>
            <span class="upvote-label text-[11px] font-medium">{{ $hasUpvoted ? 'Didukung' : 'Upvote' }}</span>
            </button>
        </form>
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

        // Gunakan URL asset eksplisit agar pin tidak bergantung pada path aplikasi.
        var reportMarkerIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.4.0/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.4.0/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Tambahkan pin marker
        L.marker([lat, lng], { icon: reportMarkerIcon }).addTo(cardMap);

        // Event listener saat area peta mini diklik
        cardMap.on('click', function(e) {
            // Hentikan penjalaran klik ke link induk card (apabila ada link overlay)
            if (e.originalEvent) {
                e.originalEvent.stopPropagation();
            }
            var googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(googleMapsUrl, '_blank');
        });

        setTimeout(function() {
            cardMap.invalidateSize();
        }, 300);
    });
</script>

<script>
    async function submitUpvote(event, form) {
        event.preventDefault();

        const button = form.querySelector('button');
        const label = form.querySelector('.upvote-label');
        const count = form.querySelector('[data-upvote-count]');
        const csrfToken = form.querySelector('input[name="_token"]').value;
        const wasUpvoted = label.textContent.trim() === 'Didukung';
        const previousCount = Number.parseInt(count.textContent, 10) || 0;
        const nextCount = Math.max(0, previousCount + (wasUpvoted ? -1 : 1));

        button.disabled = true;
        count.textContent = nextCount;
        label.textContent = wasUpvoted ? 'Upvote' : 'Didukung';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Vote gagal dikirim.');
            }

            count.textContent = data.upvote_count;
            label.textContent = data.has_upvoted ? 'Didukung' : 'Upvote';
            button.setAttribute('aria-label', data.has_upvoted ? 'Batalkan dukungan laporan' : 'Upvote laporan');
            button.classList.toggle('border-brand-400', data.has_upvoted);
            button.classList.toggle('bg-brand-50', data.has_upvoted);
            button.classList.toggle('text-brand-700', data.has_upvoted);
            button.classList.toggle('border-line', !data.has_upvoted);
            button.classList.toggle('text-ink-muted', !data.has_upvoted);
            button.disabled = false;
        } catch (error) {
            count.textContent = previousCount;
            label.textContent = wasUpvoted ? 'Didukung' : 'Upvote';
            button.disabled = false;
            alert(error.message || 'Terjadi kesalahan.');
        }
    }
</script>