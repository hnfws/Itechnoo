@props(['article'])

<article class="group overflow-hidden rounded-card border border-line bg-surface transition hover:shadow-md">
    <a href="{{ route('articles.show', $article) }}" class="block focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand-600">
        {{-- Tampilkan Foto dari DB jika ada, atau Placeholder jika kosong --}}
        @if (!empty($article->image))
            <img 
                src="{{ asset('storage/' . $article->image) }}" 
                alt="{{ $article->title }}" 
                class="aspect-video w-full object-cover"
            />
        @else
            <div class="grid aspect-video place-items-center bg-brand-50 text-xs font-medium text-brand-700">
                Gambar artikel
            </div>
        @endif

        <div class="space-y-2 p-5">
            {{-- Tanggal dibuat (Format: 12 Agustus 2026) --}}
            <p class="text-xs font-medium text-ink-muted">
                {{ $article->created_at ? $article->created_at->translatedFormat('d F Y') : '—' }}
            </p>

            {{-- Judul Artikel --}}
            <h3 class="font-semibold leading-snug text-ink transition group-hover:text-brand-700">
                {{ $article->title }}
            </h3>

            {{-- Potongan Isi Artikel (Excerpt/Summary) --}}
            <p class="line-clamp-2 text-sm text-ink-muted">
                {{ $article->excerpt ?? Str::limit(strip_tags($article->content ?? ''), 100) }}
            </p>
        </div>
    </a>
</article>