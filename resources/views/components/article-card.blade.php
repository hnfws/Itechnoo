@props(['article'])

<article class="group overflow-hidden rounded-card border border-line bg-surface transition hover:shadow-md">
    <a href="#" class="block focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand-600">
        <div class="grid aspect-video place-items-center bg-brand-50 text-xs font-medium text-brand-700">
            Gambar artikel
        </div>

        <div class="space-y-2 p-5">
            <p class="text-xs font-medium text-ink-muted">{{ $article['date'] }}</p>
            <h3 class="font-semibold leading-snug text-ink transition group-hover:text-brand-700">{{ $article['title'] }}</h3>
            <p class="line-clamp-2 text-sm text-ink-muted">{{ $article['excerpt'] }}</p>
        </div>
    </a>
</article>
