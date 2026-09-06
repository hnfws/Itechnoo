<x-layouts.admin title="Artikel">
    @push('styles')
    <style>
        .articles-desktop-table { display: none; }
        .articles-mobile-cards { display: block; }

        @media (min-width: 640px) {
            .articles-desktop-table { display: block; }
            .articles-mobile-cards { display: none; }
        }
    </style>
    @endpush

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

        <div class="articles-desktop-table overflow-x-auto">
            <table class="w-full min-w-[500px] text-left text-sm">
                <thead>
                    <tr class="border-y border-line bg-surface-muted text-ink-muted">
                        <th scope="col" class="px-5 py-3 font-medium">Gambar</th>
                        <th scope="col" class="px-5 py-3 font-medium">Judul Artikel</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tanggal</th>
                        <th scope="col" class="px-5 py-3 font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr class="border-b border-line transition hover:bg-surface-muted">
                            <td class="px-5 py-4">
                                @if ($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="" class="size-14 rounded-lg object-cover">
                                @else
                                    <div class="grid size-14 place-items-center rounded-lg bg-surface-muted text-xs text-ink-muted">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-medium text-ink">{{ $article->title }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $article->created_at?->translatedFormat('d F Y') }}</td>
                            <td class="px-5 py-4 font-medium text-green-600">{{ $article->status === 'published' ? 'Dipublikasi' : 'Draft' }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-sm font-medium text-brand-600 transition hover:text-brand-800">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="articles-mobile-cards divide-y divide-line">
            @foreach ($articles as $article)
                <article class="flex items-start gap-3 p-4">
                    @if ($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="" class="size-16 shrink-0 rounded-lg object-cover">
                    @else
                        <div class="grid size-16 shrink-0 place-items-center rounded-lg bg-surface-muted text-center text-xs text-ink-muted">Tidak ada</div>
                    @endif

                    <div class="min-w-0 flex-1 space-y-1">
                        <h3 class="break-words font-medium text-ink">{{ $article->title }}</h3>
                        <p class="text-sm text-ink-muted">{{ $article->created_at?->translatedFormat('d F Y') }}</p>
                        <p class="text-sm font-medium text-green-600">
                            {{ $article->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                        </p>
                    </div>

                    <a href="{{ route('admin.articles.edit', $article) }}" class="shrink-0 rounded-lg border border-line px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-surface-muted hover:text-brand-800">
                        Edit
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</x-layouts.admin>
