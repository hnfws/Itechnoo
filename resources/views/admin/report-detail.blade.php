@php
    $reportNumber = '#' . str_pad($report->id, 7, '0', STR_PAD_LEFT);
@endphp

<x-layouts.admin title="Laporan">
    <a
        href="{{ route('admin.reports') }}"
        class="mb-4 inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2 text-sm font-medium text-ink transition hover:bg-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
    >
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Kembali
    </a>

    @if (session('success'))
        <div class="mb-4 flex items-center gap-2 rounded-card border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Satu form besar yang membungkus status + tombol Submit/Cancel --}}
    <form
        id="report-form"
        method="POST"
        action="{{ route('admin.reports.status', $report->id) }}"
    >
        @csrf
        @method('PATCH')

        {{-- Baris atas: nomor + prioritas + dropdown status --}}
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-card border border-line bg-surface p-4 shadow-sm">
            <span class="font-semibold text-ink">Laporan {{ $reportNumber }}</span>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1 rounded-full border border-line bg-surface-muted px-4 py-2 text-sm">
                    <span class="text-ink-muted">Skala Prioritas :</span>


                    <x-admin.priority :level="$report->priority_level" />

                </span>

                <div class="inline-flex items-center gap-2 rounded-full border border-line bg-surface-muted px-4 py-1.5 text-sm">
                    <span class="text-ink-muted">Status :</span>
                    <select
                        name="status"
                        class="border-0 bg-transparent text-sm font-medium focus:outline-none {{ $report->status_color ?? '' }}"
                        aria-label="Ubah status laporan"
                    >
                        @foreach (\App\Models\Report::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected($report->status === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,320px)_1fr]">
            {{-- Kolom kiri: peta, foto, tombol aksi --}}
            <div class="space-y-4">
                {{-- Peta Leaflet --}}
                <div id="detail-map" class="h-56 w-full overflow-hidden rounded-card border border-line shadow-sm"></div>

                {{-- Foto: tampilkan gambar kalau ada, placeholder kalau belum --}}
                @if ($report->image)
                    <img
                        src="{{ asset('storage/' . $report->image) }}"
                        alt="Foto laporan"
                        class="h-56 w-full rounded-card border border-line object-cover shadow-sm"
                    >
                @else
                    <div class="grid h-56 place-items-center rounded-card border border-line bg-surface text-sm font-medium text-ink-muted shadow-sm">
                        Foto Tidak Tersedia
                    </div>
                @endif

                {{-- Submit: simpan perubahan status --}}
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
                >
                    Submit
                </button>

                {{-- Cancel: kembali ke daftar --}}
                <a
                    href="{{ route('admin.reports') }}"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-line bg-surface px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-surface-muted"
                >
                    Cancel
                </a>
            </div>

            {{-- Kolom kanan: detail laporan --}}
            <x-card>
                <div class="flex items-start justify-between gap-4">
                    <dl class="space-y-1 text-sm">
                        <div>
                            <span class="text-ink-muted">Judul Laporan :</span>
                            <span class="font-medium text-ink">{{ $report->title }}</span>
                        </div>
                        <div>
                            <span class="text-ink-muted">Nama Pelapor :</span>
                            <span class="font-medium text-ink">{{ $report->reporter ?? $report->reporter_key ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-ink-muted">Lokasi :</span>
                            <span class="font-medium text-ink">{{ $report->location }}</span>
                        </div>
                    </dl>

                    <div class="shrink-0 text-center">
                        <div class="grid size-12 place-items-center rounded-lg border border-line text-sm font-semibold text-ink tabular-nums">
                            {{ $report->upvotes_count ?? 0 }}
                        </div>
                        <span class="mt-1 block text-[11px] font-medium text-ink-muted">Upvote</span>
                    </div>
                </div>

                <p class="mt-4 text-sm text-ink-muted">Deskripsi :</p>
                <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed text-ink">
                    {{ $report->description }}
                </div>

                @if ($report->ai_adm)
                    <p class="mt-6 text-sm text-ink-muted">AI Summary :</p>
                    <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed whitespace-pre-line text-ink">
                        {{ $report->ai_adm }}
                    </div>
                @endif
            </x-card>
        </div>
    </form>

    {{-- Form Hapus terpisah --}}
    <form
        method="POST"
        action="{{ route('admin.reports.destroy', $report->id) }}"
        class="mt-4 w-full lg:w-[320px]"
        onsubmit="return confirm('Yakin ingin menghapus laporan ini?\nTindakan ini tidak bisa dibatalkan.')"
    >
        @csrf
        @method('DELETE')
        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-danger px-5 py-2.5 text-sm font-medium text-white transition hover:brightness-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger"
        >
            Hapus
        </button>
    </form>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lat = {{ $report->latitude ?? -6.200000 }};
            var lng = {{ $report->longitude ?? 106.816666 }};

            var map = L.map('detail-map', {
                zoomControl: true,
                dragging: true,
                scrollWheelZoom: false,
            }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);

            setTimeout(function () { map.invalidateSize(); }, 300);
        });
    </script>
    @endpush
</x-layouts.admin>