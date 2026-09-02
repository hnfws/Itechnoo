@props(['article'])

{{-- Ukuran diatur dari luar lewat class (aspect-*, h-full, dll.) --}}
<a
    href="#"
    {{ $attributes->class('group relative block overflow-hidden rounded-card bg-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600') }}
>
    <span class="absolute inset-0 grid place-items-center bg-gradient-to-br from-brand-100 to-brand-200 text-sm font-medium text-brand-700 transition duration-300 group-hover:scale-105">
        Berita
    </span>

    <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent p-4 pt-12">
        <span class="block text-[11px] font-medium text-white/80">{{ $article['date'] }}</span>
        <span class="mt-0.5 line-clamp-2 block font-semibold leading-snug text-white">{{ $article['title'] }}</span>
    </span>
</a>
