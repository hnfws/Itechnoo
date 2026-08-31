<div
    class="fixed inset-0 z-50 grid place-items-center overflow-y-auto bg-black/50 p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="success-title"
>
    {{-- Klik area gelap untuk menutup --}}
    <a href="{{ route('reports.index') }}" class="absolute inset-0" aria-label="Tutup"></a>

    <div class="relative w-full max-w-md rounded-2xl bg-surface p-8 text-center shadow-xl">
        <div class="mx-auto grid size-20 place-items-center rounded-full bg-green-100">
            <svg class="size-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h2 id="success-title" class="mt-6 text-2xl font-bold text-ink">Laporan anda sudah terkirim</h2>

        <p class="mt-4 text-sm leading-relaxed text-ink-muted">Laporan anda akan segera ditinjau oleh tim kami.</p>
        <p class="mt-3 text-sm leading-relaxed text-ink-muted">
            Terima kasih atas kepedulian anda terhadap lingkungan dan fasilitas umum.
        </p>
        <p class="mt-4 text-sm font-medium text-ink-muted">#Slogan</p>

        <x-button href="{{ route('reports.index') }}" autofocus class="mt-8 w-full rounded-full sm:w-auto">
            Kembali ke menu laporan
        </x-button>
    </div>
</div>
