@php
    // Data contoh sementara. Nanti backend mengirim daftar artikel dengan nama variabel yang sama.
    $articles ??= [
        ['id' => 1, 'title' => 'Cara melaporkan kerusakan fasilitas umum', 'date' => '12 Agustus 2026', 'status' => 'Dipublikasi'],
        ['id' => 2, 'title' => 'Progres perbaikan jalan bulan ini',         'date' => '8 Agustus 2026',  'status' => 'Dipublikasi'],
        ['id' => 3, 'title' => 'Kenapa laporan warga penting?',              'date' => '2 Agustus 2026',  'status' => 'Dipublikasi'],
    ];
@endphp

<x-layouts.admin title="Artikel">
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-card border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-card border border-line bg-surface shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 p-5">
            <h2 class="text-base font-semibold text-ink">Daftar Artikel</h2>
            <x-button href="{{ route('admin.articles.create') }}" size="sm" class="rounded-full">
                + Tambah Artikel
            </x-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] text-left text-sm">
                <thead>
                    <tr class="border-y border-line bg-surface-muted text-ink-muted">
                        <th scope="col" class="px-5 py-3 font-medium">Judul Artikel</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tanggal</th>
                        <th scope="col" class="px-5 py-3 font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr class="border-b border-line transition hover:bg-surface-muted">
                            <td class="px-5 py-4 font-medium text-ink">{{ $article['title'] }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $article['created_at'] }}</td>
                            <td class="px-5 py-4 font-medium text-green-600">{{ $article['status'] }}</td>
                            <td class="px-5 py-4">
                                <a href="#" class="text-sm font-medium text-brand-600 transition hover:text-brand-800">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
