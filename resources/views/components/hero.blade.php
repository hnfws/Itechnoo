<section class="relative isolate w-full overflow-hidden border-b border-line bg-surface-muted" style="height: clamp(280px, 26vw, 420px);">
    {{-- Container Peta Windy --}}
    <div id="windy" class="size-full z-0"></div>

    <aside class="report-weather-legend w-36 rounded-lg bg-black/65 p-3 text-white shadow-lg backdrop-blur-md" aria-label="Keterangan warna peta cuaca">
        <p class="text-xs font-semibold">Kecepatan angin</p>
        <p class="mt-0.5 text-[10px] text-white/75">Semakin terang, semakin kencang</p>
        <div class="mt-2 h-2 rounded-full" style="background: linear-gradient(to right, #6271b8, #4a94aa, #4ca44c, #a28740, #8d3f5c, #5f64a0);"></div>
        <div class="mt-1 flex justify-between text-[10px] text-white/80">
            <span>0 kt</span>
            <span>60 kt+</span>
        </div>
        <div class="weather-key-divider"></div>
        <p class="text-xs font-semibold">Keterangan cuaca</p>
        <div class="weather-key-list">
            <span class="weather-key-item"><i class="weather-key-dot weather-key-storm"></i>Badai / petir</span>
            <span class="weather-key-item"><i class="weather-key-dot weather-key-heavy-rain"></i>Hujan lebat</span>
            <span class="weather-key-item"><i class="weather-key-dot weather-key-rain"></i>Hujan</span>
            <span class="weather-key-item"><i class="weather-key-dot weather-key-cloudy"></i>Berawan</span>
            <span class="weather-key-item"><i class="weather-key-dot weather-key-clear"></i>Cerah</span>
        </div>
    </aside>

    {{-- Overlay Informasi --}}
    <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-black/85 via-black/50 to-transparent pt-20 pb-8">
        <x-container>
            <div class="w-fit max-w-full rounded-lg bg-black/55 px-4 py-3 backdrop-blur-md">
                <p class="text-xs font-semibold text-white" style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.95);">Selamat Datang!</p>
                <h1 class="mt-1 text-xl font-semibold text-white sm:text-2xl" style="text-shadow: 0 2px 6px rgba(0, 0, 0, 0.95);">
                    Laporkan kerusakan fasilitas umum di sekitarmu
                </h1>
            </div>
        </x-container>
    </div>
</section>

@push('styles')
<style>
    /* Menjaga z-index agar overlay gradient & marker tetap terlihat di atas canvas Windy */
    .leaflet-pane { z-index: 10 !important; }
    .leaflet-marker-pane { z-index: 1000 !important; }
    .leaflet-popup-pane { z-index: 1100 !important; }
    #windy .leaflet-road-reference-pane-pane { z-index: 450 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 20 !important; }
    #windy .windy-logo { display: none !important; }
    #windy #playpause,
    #windy #playpause-mobile {
        display: none !important;
    }
    .report-weather-legend {
        position: absolute !important;
        top: 12px !important;
        right: 12px !important;
        z-index: 2000 !important;
    }
    .weather-key-divider {
        height: 1px;
        margin: 10px 0;
        background: rgba(255, 255, 255, 0.25);
    }
    .weather-key-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 6px;
    }
    .weather-key-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
        padding: 4px 7px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 10px;
        line-height: 1;
    }
    .weather-key-dot {
        width: 8px;
        height: 8px;
        flex: 0 0 8px;
        border-radius: 9999px;
    }
    .weather-key-storm { background: #8b5cf6; }
    .weather-key-heavy-rain { background: #ef4444; }
    .weather-key-rain { background: #3b82f6; }
    .weather-key-cloudy { background: #94a3b8; }
    .weather-key-clear { background: #facc15; }

    /* CSS Styling Tag Laporan */
    .custom-report-marker {
        background: transparent !important;
        border: none !important;
    }
    .report-location-tag {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        border: 2px solid #ffffff !important;
        border-radius: 9999px !important;
        background: #dc2626 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4) !important;
        color: #ffffff !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        padding: 6px 10px !important;
        white-space: nowrap !important;
        pointer-events: auto !important;
        cursor: pointer !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reports = @json($reports);
        const WINDY_API_KEY = '{{ config('services.windy.key', 'tso7HfO1CxO0xN3GGYeJLhp8tdFMJYSW') }}';

        const options = {
            key: WINDY_API_KEY,
            lat: -7.9666204,
            lon: 112.6326321,
            zoom: 7,
            graticule: false,
            overlay: 'wind',
            product: 'gfs',
            menu: true,            
            message: true,
        };

        windyInit(options, windyAPI => {
            const { map, store } = windyAPI;

            // Overlay jalan transparan agar detail jalan muncul saat peta diperbesar.
            map.createPane('road-reference-pane');
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
                pane: 'road-reference-pane',
                maxZoom: 19,
                maxNativeZoom: 18,
                opacity: 0.9,
                attribution: '&copy; Esri World Transportation'
            }).addTo(map);

            // Mulai animasi waktu secara otomatis; tombol kontrolnya disembunyikan lewat CSS.
            setTimeout(() => {
                document.querySelector('#windy #playpause, #windy #playpause-mobile')?.click();
            }, 500);

            const markers = [];

            // Plot semua laporan
            reports.forEach(report => {
                const lat = parseFloat(report.latitude);
                const lng = parseFloat(report.longitude);

                if (!isNaN(lat) && !isNaN(lng)) {
                    const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                    
                    const reportTitle = String(report.title || 'Laporan').replace(/[&<>"']/g, c => ({
                        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                    }[c]));
                    
                    const reportLocation = String(report.location || 'Lokasi laporan').replace(/[&<>"']/g, c => ({
                        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                    }[c]));

                    // Buat Icon dengan ukuran fisik yang jelas agar tidak hilang di Windy
                    const customIcon = L.divIcon({
                        className: 'custom-report-marker',
                        html: `<div class="report-location-tag">📍 ${reportTitle}</div>`,
                        iconSize: [120, 30],      // Ukuran wadah marker
                        iconAnchor: [60, 15],     // Posisi titik tengah
                    });

                    // Buat Marker
                    const marker = L.marker([lat, lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-1 min-w-[160px]">
                                <h4 class="font-bold text-sm text-gray-900 mb-1">${reportTitle}</h4>
                                <p class="text-xs text-gray-600 mb-2">${reportLocation}</p>
                                <div class="flex flex-col gap-1.5 mt-2">
                                    <a href="${googleMapsUrl}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1 rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white hover:bg-blue-700">
                                        📍 Buka di Google Maps
                                    </a>
                                    <a href="/laporan/${report.id}" class="text-center text-xs text-gray-600 hover:text-gray-900 underline">
                                        Lihat Detail Laporan
                                    </a>
                                </div>
                            </div>
                        `);

                    markers.push(marker);
                }
            });

            // Fokus peta ke marker laporan
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
        });
    });
</script>
@endpush