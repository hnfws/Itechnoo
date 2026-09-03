
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latitude = @json($report->latitude);
        const longitude = @json($report->longitude);
        const mapElement = document.getElementById('report-map');

        if (latitude === null || longitude === null) {
            mapElement.textContent = 'Koordinat lokasi belum tersedia.';
            mapElement.classList.add('grid', 'place-items-center', 'text-sm', 'text-ink-muted');
        } else {
            const map = L.map(mapElement).setView([latitude, longitude], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            L.marker([latitude, longitude]).addTo(map);
        }

    });
</script>
@endpush

<x-layouts.app :title="'Laporan #' . str_pad($report->id, 7, '0', STR_PAD_LEFT)">    {{-- Baris atas: nomor + status + tombol kembali --}}
    <div class="border-b border-line bg-surface-muted">
        <x-container class="flex flex-wrap items-center justify-between gap-3 py-4">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1">
                    <span class="font-semibold text-ink">Laporan #{{ str_pad($report->id, 7, '0', STR_PAD_LEFT) }}</span>                <span class="text-sm text-ink-muted">
                    Status :
                    <span class="font-medium text-green-600">{{ $report->status }}</span>
                </span>
            </div>

            <x-button href="{{ route('reports.index') }}" variant="secondary" size="sm" class="rounded-full">
                Kembali
            </x-button>
        </x-container>
    </div>

    <x-container class="py-8">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,340px)_1fr]">
            {{-- Kolom kiri: peta + foto --}}
            <div class="space-y-6">
                <div id="report-map" class="h-56 rounded-card border border-line bg-surface-muted"></div>
                <div class="h-56 overflow-hidden rounded-card border border-line bg-surface-muted">
    <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" class="h-full w-full object-cover">
</div>
            </div>

            {{-- Kolom kanan: detail laporan --}}
            <x-card>
                <dl class="space-y-1 text-sm">
                    <div>
                        <span class="text-ink-muted">Judul Laporan :</span>
                        <span class="font-medium text-ink">{{ $report->title }}</span>
                    </div>
                    <div>
                        <span class="text-ink-muted">Nama Pelapor :</span>
                        <span class="font-medium text-ink">{{ $report->reporter }}</span>
                    </div>
                    <div>
                        <span class="text-ink-muted">Lokasi :</span>
                        <span class="font-medium text-ink">{{ $report->location }}</span>
                    </div>
                </dl>

                <p class="mt-4 text-sm text-ink-muted">Deskripsi :</p>
                <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed text-ink">
                    {{ $report->description }}
                </div>

                <p class="mt-6 text-sm text-ink-muted">AI Summary :</p>
                <div class="mt-1 rounded-lg border border-line bg-surface p-4 text-sm leading-relaxed whitespace-pre-line text-ink">
                    {{ $report->ai_masyarakat ?: 'Analisis AI belum tersedia. Silakan kirim ulang laporan.' }}
                </div>
                @if (! $report->ai_masyarakat)
                    <form action="{{ route('reports.reanalyze', $report->id) }}" method="POST" class="mt-3">
                        @csrf
                        <x-button type="submit" size="sm" variant="outline">Coba Analisis Lagi</x-button>
                    </form>
                @endif
            </x-card>
        </div>
    </x-container>
</x-layouts.app>
