@php
    // Data contoh sementara. Nanti backend mengirim daftar laporan dengan nama variabel yang sama.
    $reports ??= array_fill(0, 4, [
        'id' => 1,
        'title' => 'Jalan rusak',
        'location' => 'Jl Letjen Soeprapto',
        'description' => 'Jalan rusak bolong bolong sudah ada korban 2 pemotor terjatuh. Tolong segera dibenerkan agar tidak ada korban lagi',
        'upvotes' => 128,
    ]);
@endphp

<x-layouts.app title="Menu Laporan">
    {{-- Banner pengumuman (sementara pakai placeholder, ganti dengan <img> kalau gambarnya sudah ada) --}}
    <div class="border-b border-line bg-surface-muted py-8">
        <x-container>
            <div class="mx-auto grid aspect-[4/1] max-w-3xl place-items-center rounded-card bg-brand-50 text-sm font-medium text-brand-700">
                Banner Pengumuman
            </div>
        </x-container>
    </div>

    <x-container class="py-10">
        <x-section-heading title="Menu Laporan">
            <x-slot:action>
                <x-button href="{{ route('reports.create') }}" size="sm" class="rounded-full">Buat Laporan</x-button>
            </x-slot:action>
        </x-section-heading>

        <div class="mt-6 space-y-4">
            @foreach ($reports as $report)
                <x-report-card :report="$report" />
            @endforeach
        </div>
    </x-container>
</x-layouts.app>
