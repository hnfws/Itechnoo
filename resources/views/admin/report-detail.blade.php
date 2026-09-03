@php
    $reportId ??= '1';

    // Data contoh sementara. Nanti backend mengirim data laporan dengan nama variabel yang sama.
    $report ??= [
        'title' => 'Jalan rusak',
        'reporter' => 'Adi Cahyadi',
        'location' => 'Jl. Letjen Soeprapto',
        'priority' => 'rendah',
        'status' => 'Terverifikasi',
        'upvotes' => 128,
        'description' => 'Jalan rusak bolong bolong sudah ada korban 2 pemotor terjatuh. Tolong segera dibenerkan agar tidak ada korban lagi',
        'ai_summary' => "Permukaan jalan tampak tidak beraspal utuh dan dipenuhi lumpur tebal. Terdapat beberapa genangan air berukuran sedang hingga besar di sepanjang jalur. Kondisi jalan tidak rata dengan hamparan batu lepas dan cekungan dalam.\n\nBerdasarkan laporan, sekitar 2 kendaraan roda dua telah tergelincir saat melewati jalan ini.\n\nRekomendasi :\nHarap menggunakan jalan alternatif lain, untuk mengurangi angka kecelakaan. Apabila mungkin dan berkenan, harap meletakkan penanda jalan pada jalan yang bolong untuk mencegah pengendara lain mengalami hal yang tidak diinginkan.",
    ];

    $reportNumber = '#' . str_pad($reportId, 7, '0', STR_PAD_LEFT);
@endphp

<x-layouts.admin title="Laporan">
    {{-- Baris atas: nomor + prioritas + status --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-card border border-line bg-surface p-4 shadow-sm">
        <span class="font-semibold text-ink">Laporan {{ $reportNumber }}</span>

        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1 rounded-full border border-line bg-surface-muted px-4 py-2 text-sm">
                <span class="text-ink-muted">Skala Prioritas :</span>
                <x-admin.priority :level="$report['priority']" />
            </span>

            {{-- Pengubah status (dropdown-nya butuh JavaScript, disambungkan nanti) --}}
            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-line bg-surface-muted px-4 py-2 text-sm">
                <span class="text-ink-muted">Status :</span>
                <span class="font-medium text-green-600">{{ $report['status'] }}</span>
                <svg class="size-4 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,320px)_1fr]">
        {{-- Kolom kiri: peta, foto, aksi --}}
        <div class="space-y-4">
            <div class="grid h-56 place-items-center rounded-card border border-line bg-surface text-sm font-medium text-ink-muted shadow-sm">Map</div>
            <div class="grid h-56 place-items-center rounded-card border border-line bg-surface text-sm font-medium text-ink-muted shadow-sm">Foto</div>

            {{-- Submit & Hapus disambungkan backend. Cancel kembali ke daftar. --}}
            <x-button type="button" class="w-full">Submit</x-button>
            <x-button href="{{ route('admin.reports') }}" variant="outline" class="w-full">Cancel</x-button>
            <x-button type="button" variant="danger" class="w-full">Hapus</x-button>
        </div>

        {{-- Kolom kanan: detail --}}
        <x-card>
            <div class="flex items-start justify-between gap-4">
                <dl class="space-y-1 text-sm">
                    <div><span class="text-ink-muted">Judul Laporan :</span> <span class="font-medium text-ink">{{ $report['title'] }}</span></div>
                    <div><span class="text-ink-muted">Nama Pelapor :</span> <span class="font-medium text-ink">{{ $report['reporter'] }}</span></div>
                    <div><span class="text-ink-muted">Lokasi :</span> <span class="font-medium text-ink">{{ $report['location'] }}</span></div>
                    <div><span class="text-ink-muted">Deskripsi :</span> <span class="font-medium text-ink">{{ $report['description'] }}</span></div>
                </dl>

                <div class="shrink-0 text-center">
                    <div class="grid size-12 place-items-center rounded-lg border border-line text-sm font-semibold text-ink tabular-nums">
                        {{ $report['upvotes'] }}
                    </div>
                    <span class="mt-1 block text-[11px] font-medium text-ink-muted">Upvote</span>
                </div>
            </div>

            <p class="mt-4 text-sm text-ink-muted">Deskripsi :</p>
            <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed text-ink">
                {{ $report['description'] }}
            </div>

            <p class="mt-6 text-sm text-ink-muted">AI Summary :</p>
            <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed whitespace-pre-line text-ink">
                {{ $report['ai_summary'] }}
            </div>
        </x-card>
    </div>
</x-layouts.admin>
