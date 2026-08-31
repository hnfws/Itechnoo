@php
    // Data contoh sementara. Nanti backend mengirim daftar artikel dengan nama variabel yang sama.
    $mk = fn ($t) => ['title' => $t, 'date' => '12 Agustus 2026'];

    $featured ??= $mk('Perbaikan jalan utama kota dimulai pekan ini');
    $secondary ??= [
        $mk('Warga gotong royong bersihkan saluran air'),
        $mk('Lampu jalan baru dipasang di 12 titik'),
        $mk('Halte bus direnovasi tahun ini'),
        $mk('Program tambal jalan berlubang diperluas'),
    ];
    $wide ??= $mk('Laporan warga bantu percepat perbaikan fasilitas umum');
    $more ??= [
        $mk('Trotoar ramah pejalan kaki'),
        $mk('Taman kota dihidupkan kembali'),
        $mk('Drainase baru kurangi banjir'),
        $mk('Jembatan penyeberangan diperbaiki'),
    ];
@endphp

<x-layouts.app title="Artikel">
    <x-container class="py-10">
        <h1 class="text-center text-xl font-semibold tracking-tight text-ink sm:text-2xl">Artikel</h1>

        {{-- Baris 1: satu besar + empat kecil --}}
        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <x-article-tile :article="$featured" class="min-h-[280px] lg:h-full" />

            <div class="grid grid-cols-2 gap-6">
                @foreach ($secondary as $article)
                    <x-article-tile :article="$article" class="aspect-[16/10]" />
                @endforeach
            </div>
        </div>

        {{-- Baris 2: melebar penuh --}}
        <div class="mt-6">
            <x-article-tile :article="$wide" class="aspect-[21/7] min-h-[200px]" />
        </div>

        {{-- Baris 3: empat kotak --}}
        <div class="mt-6 grid grid-cols-2 gap-6 sm:grid-cols-4">
            @foreach ($more as $article)
                <x-article-tile :article="$article" class="aspect-square" />
            @endforeach
        </div>
    </x-container>
</x-layouts.app>
