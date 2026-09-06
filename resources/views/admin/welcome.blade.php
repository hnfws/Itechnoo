@php
    // Nanti backend mengganti dengan nama admin yang sedang login: auth()->user()->name
    $adminName ??= 'Endministrator';
@endphp

<x-layouts.guest title="Selamat datang">
    @push('head')
        <meta http-equiv="refresh" content="3;url={{ route('admin.dashboard') }}">
    @endpush

    <div class="text-center">
        {{-- Logo --}}
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="size-28 object-contain">
        </div>

        <h1 class="text-3xl font-bold tracking-tight text-ink">Welcome back, {{ $adminName }}.</h1>

        <figure class="mx-auto mt-4 max-w-md">
            <blockquote class="text-sm leading-relaxed text-ink-muted">
                &ldquo;Satu-satunya cara untuk melakukan pekerjaan hebat adalah dengan mencintai apa yang Anda lakukan.
                Jika Anda belum menemukannya, teruslah mencari. Jangan puas.&rdquo;
            </blockquote>
            <figcaption class="mt-3 text-sm italic text-ink-muted">&ndash; Steve Jobs</figcaption>
        </figure>

        <p class="mt-8 text-sm text-ink-muted">
            Mengalihkan ke dashboard&hellip;
            <a href="{{ route('admin.dashboard') }}" class="font-medium text-brand-600 hover:underline">Klik di sini</a>
            jika tidak berpindah otomatis.
        </p>
    </div>
</x-layouts.guest>
