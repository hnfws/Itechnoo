<x-layouts.app title="Artikel">
    <x-container class="py-10">
        <h1 class="text-center text-xl font-semibold tracking-tight text-ink sm:text-2xl">Artikel</h1>

        @if ($articles->isEmpty())
            <p class="mt-8 text-center text-sm text-ink-muted">Belum ada artikel yang dipublikasi.</p>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
    </x-container>
</x-layouts.app>
