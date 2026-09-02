<x-layouts.admin title="Artikel">
    <div class="rounded-card border border-line bg-surface p-6 shadow-sm">
        <h2 class="mb-6 font-semibold text-ink">Tambah Artikel</h2>

        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Upload / Drop gambar --}}
            <div>
                <label
                    for="image"
                    class="grid min-h-44 cursor-pointer place-items-center rounded-card border-2 border-dashed border-line bg-surface text-center transition hover:border-brand-400 hover:bg-brand-50"
                >
                    <div class="space-y-2 p-6">
                        <svg class="mx-auto size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm font-medium text-ink">upload / Drop Image</p>
                        <p class="text-xs text-ink-muted">Klik atau seret gambar ke sini (JPG atau PNG)</p>
                    </div>
                </label>
                <input id="image" name="image" type="file" accept="image/*" class="sr-only">
            </div>

            <x-input name="title" label="Judul Artikel" placeholder="Tulis judul artikel" class="rounded-full" />

            <x-textarea name="content" label="Isi Artikel" :rows="12" placeholder="Tulis isi artikel di sini..." />

            <div class="flex flex-wrap justify-center gap-4">
                <x-button href="{{ route('admin.articles') }}" variant="outline" class="min-w-36 rounded-full">
                    Cancel
                </x-button>
                <x-button type="submit" class="min-w-36 rounded-full">
                    Submit
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>
