@php
    $reportId ??= '1';

    // Data contoh sementara. Nanti backend mengirim data laporan dengan nama variabel yang sama.
    $report ??= [
        'title' => 'Jalan rusak',
        'reporter' => 'Adi Cahyadi',
        'location' => 'Jl. Letjen Soeprapto',
        'status' => 'Terverifikasi',
        'description' => 'Jalan rusak bolong bolong sudah ada korban 2 pemotor terjatuh. Tolong segera dibenerkan agar tidak ada korban lagi',
        'ai_summary' => "Permukaan jalan tampak tidak beraspal utuh dan dipenuhi lumpur tebal. Terdapat beberapa genangan air berukuran sedang hingga besar di sepanjang jalur. Kondisi jalan tidak rata dengan hamparan batu lepas dan cekungan dalam.\n\nBerdasarkan laporan, sekitar 2 kendaraan roda dua telah tergelincir saat melewati jalan ini.\n\nRekomendasi :\nHarap menggunakan jalan alternatif lain, untuk mengurangi angka kecelakaan. Apabila mungkin dan berkenan, harap meletakkan penanda jalan pada jalan yang bolong untuk mencegah pengendara lain mengalami hal yang tidak diinginkan.",
    ];

    $reportNumber = '#' . str_pad($reportId, 7, '0', STR_PAD_LEFT);
@endphp

<x-layouts.app :title="'Laporan ' . $reportNumber">
    {{-- Baris atas: nomor + status + tombol kembali --}}
    <div class="border-b border-line bg-surface-muted">
        <x-container class="flex flex-wrap items-center justify-between gap-3 py-4">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1">
                <span class="font-semibold text-ink">Laporan {{ $reportNumber }}</span>
                <span class="text-sm text-ink-muted">
                    Status :
                    <span class="font-medium text-green-600">{{ $report['status'] }}</span>
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
                <div class="grid h-56 place-items-center rounded-card border border-line bg-surface-muted text-sm font-medium text-ink-muted">
                    Map
                </div>
                <div class="grid h-56 place-items-center rounded-card border border-line bg-surface-muted text-sm font-medium text-ink-muted">
                    Foto
                </div>
            </div>

            {{-- Kolom kanan: detail laporan --}}
            <x-card>
                <dl class="space-y-1 text-sm">
                    <div>
                        <span class="text-ink-muted">Judul Laporan :</span>
                        <span class="font-medium text-ink">{{ $report['title'] }}</span>
                    </div>
                    <div>
                        <span class="text-ink-muted">Nama Pelapor :</span>
                        <span class="font-medium text-ink">{{ $report['reporter'] }}</span>
                    </div>
                    <div>
                        <span class="text-ink-muted">Lokasi :</span>
                        <span class="font-medium text-ink">{{ $report['location'] }}</span>
                    </div>
                </dl>

                <p class="mt-4 text-sm text-ink-muted">Deskripsi :</p>
                <div class="mt-1 rounded-lg bg-brand-50 p-4 text-sm leading-relaxed text-ink">
                    {{ $report['description'] }}
                </div>

                <p class="mt-6 text-sm text-ink-muted">AI Summary :</p>
                <div class="mt-1 rounded-lg border border-line bg-surface p-4 text-sm leading-relaxed whitespace-pre-line text-ink">
                    {{ $report['ai_summary'] }}
                </div>
            </x-card>
        </div>
    </x-container>
</x-layouts.app>
