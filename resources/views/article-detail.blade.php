<x-layouts.app :title="$artikel->title">
    <article class="mx-auto max-w-4xl px-6 py-10 sm:px-8 lg:py-14">
        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-600 transition hover:text-brand-800">
            <span aria-hidden="true">&larr;</span>
            Kembali ke artikel
        </a>

        <header class="mt-8 border-b border-line pb-8">
            <p class="text-sm font-medium text-ink-muted">
                {{ $artikel->created_at?->translatedFormat('d F Y') }}
            </p>
            <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-ink sm:text-4xl">
                {{ $artikel->title }}
            </h1>
        </header>

        @if ($artikel->image)
            <div class="mt-8 aspect-video w-full overflow-hidden rounded-card bg-transparent">
                <img
                    src="{{ asset('storage/' . $artikel->image) }}"
                    alt="{{ $artikel->title }}"
                    class="size-full object-contain"
                >
            </div>
        @endif

        <div class="prose prose-slate mt-8 max-w-none text-ink [&_a]:text-brand-600 [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-brand-400 [&_blockquote]:pl-4 [&_li]:ml-5 [&_ol]:list-decimal [&_p]:my-4 [&_strong]:font-semibold [&_ul]:list-disc">
            {!! $artikel->content !!}
        </div>
    </article>
</x-layouts.app>
