<section class="relative isolate aspect-[16/7] min-h-[320px] w-full overflow-hidden border-b border-line bg-surface-muted">
    {{-- Container Peta --}}
    <div id="overview-map" class="size-full z-0"></div>

    {{-- Overlay Informasi --}}
    <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-black/80 via-black/40 to-transparent pt-20 pb-8">
        <x-container>
            <p class="text-xs font-medium text-white/80">Kota Malang</p>
            <h1 class="mt-1 text-xl font-semibold text-white sm:text-2xl">
                Laporkan kerusakan fasilitas umum di sekitarmu
            </h1>
        </x-container>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Menjaga z-index bawaan Leaflet agar berada di belakang overlay gradient */
    .leaflet-pane { z-index: 1 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 2 !important; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Koordinat default (Kota Malang)
        const defaultLat = -7.9666204;
        const defaultLng = 112.6326321;

        const map = L.map('overview-map').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Ambil data laporan dari Blade
        const reports = @json($reports);
        const markers = [];

        // Looping semua laporan dan tambahkan marker ke peta
        reports.forEach(report => {
            if (report.latitude && report.longitude) {
                // Buat URL Google Maps berdasarkan latitude & longitude
                const googleMapsUrl = `https://www.google.com/maps?q=${report.latitude},${report.longitude}`;

                const marker = L.marker([report.latitude, report.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-1 min-w-[160px]">
                            <h4 class="font-bold text-sm text-gray-900 mb-1">${report.title}</h4>
                            <p class="text-xs text-gray-600 mb-2">${report.location}</p>
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

        // Otomatis atur zoom peta agar semua marker terlihat sekaligus
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    });
</script>
@endpush
