@php
    $isEdit = isset($article);
    $formAction = $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store');
@endphp

<x-layouts.admin title="Artikel">
    <div class="rounded-card border border-line bg-surface p-6 shadow-sm">
        <h2 class="mb-6 font-semibold text-ink">{{ $isEdit ? 'Edit Artikel' : 'Tambah Artikel' }}</h2>

        {{-- Menampilkan pesan error validasi global jika ada --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if ($isEdit)
                @method('PATCH')
            @endif

            {{-- Upload / Drop gambar --}}
            <div>
                <label
                    for="image"
                    data-image-dropzone
                    class="relative grid h-64 cursor-pointer place-items-center overflow-hidden rounded-card border-2 border-dashed border-line bg-surface text-center transition hover:border-brand-400 hover:bg-brand-50"
                >
                    <div data-image-placeholder class="space-y-2 p-6 {{ $isEdit && $article->image ? 'hidden' : '' }}">
                        <svg class="mx-auto size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm font-medium text-ink">Upload / Drop Image</p>
                        <p class="text-xs text-ink-muted">Klik atau seret gambar ke sini (JPG atau PNG)</p>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center p-2">
                        <img data-image-preview alt="Preview gambar artikel" src="{{ $isEdit && $article->image ? asset('storage/' . $article->image) : '' }}" class="{{ $isEdit && $article->image ? '' : 'hidden' }} size-full object-contain">
                    </div>
                    <span data-image-name class="absolute bottom-2 left-2 right-2 {{ $isEdit && $article->image ? '' : 'hidden' }} truncate rounded-md bg-black/65 px-3 py-1.5 text-xs text-white">{{ $isEdit && $article->image ? basename($article->image) : '' }}</span>
                </label>
                <input id="image" name="image" type="file" accept="image/*" class="sr-only">
            </div>

            <x-input name="title" label="Judul Artikel" placeholder="Tulis judul artikel" class="rounded-full" value="{{ old('title', $article->title ?? '') }}" />

            <div class="space-y-1.5">
                <label for="content-editor" class="block text-sm font-medium text-ink">Isi Artikel</label>
                <div class="overflow-hidden rounded-lg border border-line bg-surface focus-within:outline-2 focus-within:outline-brand-600">
                    <div class="flex flex-wrap items-center gap-1 border-b border-line bg-surface-muted p-2" role="toolbar" aria-label="Format isi artikel">
                        <select data-editor-command="fontName" class="h-9 rounded-md border border-line bg-surface px-2 text-sm text-ink" aria-label="Jenis font">
                            <option value="Instrument Sans">Instrument Sans</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Arial">Arial</option>
                            <option value="Courier New">Courier New</option>
                        </select>
                        <select data-editor-command="fontSize" class="h-9 rounded-md border border-line bg-surface px-2 text-sm text-ink" aria-label="Ukuran font">
                            <option value="3">Normal</option>
                            <option value="2">Kecil</option>
                            <option value="4">Besar</option>
                            <option value="5">Sangat besar</option>
                        </select>
                        <span class="mx-1 h-6 w-px bg-line" aria-hidden="true"></span>
                        @foreach ([['bold', 'B', 'Tebal'], ['italic', 'I', 'Miring'], ['underline', 'U', 'Garis bawah'], ['strikeThrough', 'S', 'Coret']] as [$command, $label, $title])
                            <button type="button" data-editor-command="{{ $command }}" class="grid size-9 place-items-center rounded-md text-sm font-semibold text-ink-muted transition hover:bg-surface hover:text-ink" title="{{ $title }}" aria-label="{{ $title }}">{{ $label }}</button>
                        @endforeach
                        <span class="mx-1 h-6 w-px bg-line" aria-hidden="true"></span>
                        @foreach ([['justifyLeft', 'Kiri', 'Rata kiri'], ['justifyCenter', 'Tengah', 'Rata tengah'], ['justifyRight', 'Kanan', 'Rata kanan'], ['insertUnorderedList', '• List', 'Daftar bullet'], ['insertOrderedList', '1. List', 'Daftar bernomor']] as [$command, $label, $title])
                            <button type="button" data-editor-command="{{ $command }}" class="h-9 rounded-md px-2 text-sm text-ink-muted transition hover:bg-surface hover:text-ink" title="{{ $title }}" aria-label="{{ $title }}">{{ $label }}</button>
                        @endforeach
                        <span class="mx-1 h-6 w-px bg-line" aria-hidden="true"></span>
                        <button type="button" data-editor-command="undo" class="grid size-9 place-items-center rounded-md text-sm text-ink-muted transition hover:bg-surface hover:text-ink" title="Urungkan" aria-label="Urungkan">Undo</button>
                        <button type="button" data-editor-command="redo" class="grid size-9 place-items-center rounded-md text-sm text-ink-muted transition hover:bg-surface hover:text-ink" title="Ulangi" aria-label="Ulangi">Redo</button>
                    </div>
                    <div id="content-editor" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Isi Artikel" data-rich-editor data-placeholder="Tulis isi artikel di sini..." class="min-h-72 w-full px-3 py-2.5 text-sm text-ink outline-none empty:before:text-ink-muted empty:before:content-[attr(data-placeholder)]">{!! old('content', $article->content ?? '') !!}</div>
                    <textarea id="content" name="content" class="sr-only" tabindex="-1" aria-hidden="true">{{ old('content', $article->content ?? '') }}</textarea>
                </div>
                @error('content')
                    <p id="content-error" class="text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                <x-button href="{{ route('admin.articles') }}" variant="outline" class="min-w-36 rounded-full">
                    Cancel
                </x-button>
                <x-button type="submit" class="min-w-36 rounded-full">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Submit' }}
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.admin>