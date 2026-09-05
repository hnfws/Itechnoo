<x-layouts.app title="Beranda">
    {{-- 1. Mengoper $mapReports (Semua lokasi) ke Peta --}}
    <x-hero :reports="$mapReports" />

    <x-announcement-bar :reports="$inProgressReports" />

    <x-container class="py-10">
        <x-section-heading title="Laporan Pengaduan">
            <x-slot:action>
                <x-button href="{{ route('reports.index') }}" variant="secondary" size="sm" class="rounded-full">Menu Laporan</x-button>
            </x-slot:action>
        </x-section-heading>

        <div class="mt-6 space-y-4">
            {{-- 2. Menggunakan $latestReports (2 Laporan Terbaru) --}}
            @foreach ($latestReports as $report)
                <x-report-card :report="$report" />
            @endforeach
        </div>
    </x-container>

    <section class="border-t border-line bg-surface-muted py-10">
        <x-container>
            <x-section-heading title="Artikel" />

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($articles as $article)
                    <x-article-card :article="$article" />
                @empty
                    <p class="col-span-full text-center text-ink-muted">Belum ada artikel yang diterbitkan.</p>
                @endforelse
            </div>

            <div class="mt-8 flex justify-center">
                <x-button href="{{ route('articles.index') }}" variant="outline" class="rounded-full">Selengkapnya</x-button>
            </div>
        </x-container>
    </section>
</x-layouts.app>