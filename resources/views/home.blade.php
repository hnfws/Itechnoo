@php
    // Data contoh sementara untuk menampilkan layout.
    // Nanti backend tinggal mengirim data dengan nama variabel yang sama.
    $announcement ??= 'Saat ini sedang dilakukan perbaikan jalan di Jl Letjen Soeprapto. Mohon maaf apabila perbaikan saat ini mengganggu perjalanan anda. Harap gunakan jalur alternatif.';

    $reports ??= [
        [
            'id' => 1,
            'title' => 'Jalan rusak',
            'location' => 'Jl Letjen Soeprapto',
            'description' => 'Jalan rusak bolong bolong sudah ada korban 2 pemotor terjatuh. Tolong segera dibenerkan agar tidak ada korban lagi',
            'upvotes' => 128,
        ],
        [
            'id' => 2,
            'title' => 'Halte Pinggir Jalan',
            'location' => 'Jl Letjen Soeprapto',
            'description' => 'Halte kuning yang sudah mulai tua, membutuhkan perbaikan segera. Saat duduk di halte karat nya nempel di baju dan celana.',
            'upvotes' => 42,
        ],
    ];

    $articles ??= [
        ['title' => 'Cara melaporkan kerusakan fasilitas umum', 'date' => '12 Agustus 2026', 'excerpt' => 'Panduan singkat mengirim laporan agar cepat ditindaklanjuti petugas.'],
        ['title' => 'Progres perbaikan jalan bulan ini', 'date' => '8 Agustus 2026', 'excerpt' => 'Rekap titik perbaikan yang sudah selesai dikerjakan di wilayah kota.'],
        ['title' => 'Kenapa laporan warga penting?', 'date' => '2 Agustus 2026', 'excerpt' => 'Data dari warga membantu prioritas anggaran perbaikan infrastruktur.'],
    ];
@endphp

<x-layouts.app title="Beranda">
    <x-hero />

    <x-announcement-bar :text="$announcement" />

    <x-container class="py-10">
        <x-section-heading title="Laporan Pengaduan">
            <x-slot:action>
                <x-button href="{{ route('reports.index') }}" variant="secondary" size="sm" class="rounded-full">Menu Laporan</x-button>
            </x-slot:action>
        </x-section-heading>

        <div class="mt-6 space-y-4">
            @foreach ($reports as $report)
                <x-report-card :report="$report" />
            @endforeach
        </div>
    </x-container>

    <section class="border-t border-line bg-surface-muted py-10">
        <x-container>
            <x-section-heading title="Artikel" />

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" />
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                <x-button href="{{ route('articles.index') }}" variant="outline" class="rounded-full">Selengkapnya</x-button>
            </div>
        </x-container>
    </section>
</x-layouts.app>
