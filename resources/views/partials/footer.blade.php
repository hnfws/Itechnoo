<footer class="border-t border-line bg-surface py-8">
    <x-container class="flex flex-col gap-2 text-sm text-ink-muted sm:flex-row sm:items-center sm:justify-between">
        <p>&copy; {{ now()->year }} {{ config('app.name') }}. Semua hak dilindungi.</p>

        <nav aria-label="Navigasi footer">
            <ul class="flex gap-4">
                <li><a href="#" class="transition hover:text-ink">Tentang</a></li>
                <li><a href="#" class="transition hover:text-ink">Kontak</a></li>
            </ul>
        </nav>
    </x-container>
</footer>
