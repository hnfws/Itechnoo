<x-layouts.app title="Form Laporan">
    <div class="border-b border-line bg-surface-muted">
        <x-container class="py-4">
            <h1 class="font-semibold text-ink">Form Laporan</h1>
        </x-container>
    </div>

    <x-container class="py-8">
        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Upload gambar --}}
            <div>
                <label
                    for="image"
                    class="grid min-h-56 cursor-pointer place-items-center rounded-card border-2 border-dashed border-line bg-surface text-center transition hover:border-brand-400 hover:bg-brand-50"
                >
                    <div class="space-y-2 p-6">
                        <svg class="mx-auto size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm font-medium text-ink">Upload Image Box</p>
                        <p class="text-xs text-ink-muted">Klik untuk memilih foto kerusakan (JPG atau PNG)</p>
                    </div>
                </label>
                <input id="image" name="image" type="file" accept="image/*" class="sr-only">
            </div>

            <x-input name="title" label="Judul Laporan" placeholder="Contoh: Jalan rusak di Jl Letjen Soeprapto" />

            <div class="grid gap-6 sm:grid-cols-2">
                <x-input name="reporter" label="Nama Pelapor" placeholder="Nama lengkap" />
                <x-input name="phone" type="tel" label="No Telepon/WhatsApp" placeholder="08xxxxxxxxxx" />
            </div>

            <x-textarea name="description" label="Deskripsi" rows="5" placeholder="Jelaskan kondisi kerusakan sedetail mungkin" />

            {{-- Lokasi --}}
            <div class="space-y-1.5">
                <span class="block text-sm font-medium text-ink">Lokasi</span>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="grid min-h-44 place-items-center rounded-card bg-surface-muted text-sm font-medium text-ink-muted">
                        Map GPS
                    </div>
                    <div>
                        <x-input name="location" placeholder="Ketik alamat lokasi" />
                    </div>
                </div>
            </div>

            {{-- Persetujuan --}}
            <div class="space-y-3">
                <x-checkbox name="show_name">Tampilkan nama pada laporan</x-checkbox>
                <x-checkbox name="agreement">
                    Dengan ini saya menyatakan laporan yang saya buat benar sebenar benarnya, dan siap menerima
                    hukuman sesuai dengan undang undang yang berlaku apabila laporan bersifat palsu.
                </x-checkbox>
            </div>

            <x-button type="submit" class="rounded-full">Kirim Laporan</x-button>
        </form>
    </x-container>
</x-layouts.app>
