@php
    // Data contoh sementara. Nanti backend mengirim daftar laporan dengan nama variabel yang sama.
    $reports ??= array_fill(0, 4, [
        'id' => 1,
        'title' => 'Jalan rusak',
        'location' => 'Jl Letjen Soeprapto',
        'description' => 'Jalan rusak bolong bolong sudah ada korban 2 pemotor terjatuh. Tolong segera dibenerkan agar tidak ada korban lagi',
        'upvotes' => 128,
    ]);
@endphp

@push('styles')
<style>
    .announcement-carousel {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1307 / 568;   /* diubah dari 1307/825 → sesuai ukuran asli gambar */
    max-width: 100%;            /* diubah dari 42rem → full lebar container */
    margin-inline: auto;
    border: 1px solid var(--color-line);
    border-radius: var(--radius-card);
    background: var(--color-brand-50);
    touch-action: pan-y;
}

    .announcement-track {
        display: flex;
        height: 100%;
        transition: transform 400ms ease;
    }

    .announcement-slide {
        position: relative;
        min-width: 100%;
        height: 100%;
    }

    .announcement-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .announcement-control {
        position: absolute;
        top: 50%;
        z-index: 2;
        display: grid;
        width: 2.25rem;
        height: 2.25rem;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 9999px;
        background: rgba(32, 38, 79, 0.72);
        color: #fff;
        transform: translateY(-50%);
    }

    .announcement-control:hover { background: rgba(32, 38, 79, 0.92); }
    .announcement-control-prev { left: 0.75rem; }
    .announcement-control-next { right: 0.75rem; }

    .announcement-dots {
        position: absolute;
        right: 0;
        bottom: 0.75rem;
        left: 0;
        z-index: 2;
        display: flex;
        justify-content: center;
        gap: 0.4rem;
    }

    .announcement-dot {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.65);
    }

    .announcement-dot.is-active { background: #fff; transform: scale(1.25); }

    @media (max-width: 639px) {
        .announcement-control { width: 2rem; height: 2rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('[data-announcement-carousel]');
        if (!carousel) return;

        const track = carousel.querySelector('[data-announcement-track]');
        const slides = [...carousel.querySelectorAll('[data-announcement-slide]')];
        const dots = [...carousel.querySelectorAll('[data-announcement-dot]')];
        let activeIndex = 0;
        let timer;
        let startX = 0;

        const showSlide = index => {
            activeIndex = (index + slides.length) % slides.length;
            track.style.transform = `translateX(-${activeIndex * 100}%)`;
            dots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === activeIndex));
        };

        const restartAutoPlay = () => {
            clearInterval(timer);
            timer = setInterval(() => showSlide(activeIndex + 1), 5000);
        };

        carousel.querySelector('[data-announcement-prev]').addEventListener('click', () => {
            showSlide(activeIndex - 1);
            restartAutoPlay();
        });
        carousel.querySelector('[data-announcement-next]').addEventListener('click', () => {
            showSlide(activeIndex + 1);
            restartAutoPlay();
        });
        dots.forEach((dot, index) => dot.addEventListener('click', () => {
            showSlide(index);
            restartAutoPlay();
        }));

        carousel.addEventListener('pointerdown', event => { startX = event.clientX; });
        carousel.addEventListener('pointerup', event => {
            const distance = event.clientX - startX;
            if (Math.abs(distance) < 40) return;
            showSlide(activeIndex + (distance < 0 ? 1 : -1));
            restartAutoPlay();
        });

        showSlide(0);
        restartAutoPlay();
    });
</script>
@endpush

<x-layouts.app title="Menu Laporan">
    {{-- Banner pengumuman --}}
    <div class="border-b border-line bg-surface-muted py-8">
        <x-container>
            <div class="announcement-carousel" data-announcement-carousel aria-label="Banner pengumuman">
                <div class="announcement-track" data-announcement-track>
                    @foreach (['banner-1.png', 'banner-2.png', 'banner-3.png', 'banner-4.png'] as $banner)
                        <div class="announcement-slide" data-announcement-slide>
                            <img src="{{ asset('images/' . $banner) }}" alt="Banner pengumuman {{ $loop->iteration }}">
                        </div>
                    @endforeach
                </div>

                <button type="button" class="announcement-control announcement-control-prev" data-announcement-prev aria-label="Banner sebelumnya">&#8592;</button>
                <button type="button" class="announcement-control announcement-control-next" data-announcement-next aria-label="Banner berikutnya">&#8594;</button>

                <div class="announcement-dots" aria-label="Pilih banner">
                    @foreach (range(0, 3) as $index)
                        <button type="button" class="announcement-dot{{ $index === 0 ? ' is-active' : '' }}" data-announcement-dot aria-label="Buka banner {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        </x-container>
    </div>

    <x-container class="py-10">
        <x-section-heading title="Menu Laporan">
            <x-slot:action>
                <x-button href="{{ route('reports.create') }}" size="sm" class="rounded-full">Buat Laporan</x-button>
            </x-slot:action>
        </x-section-heading>

        <div class="mt-6 space-y-4">
            @foreach ($reports as $report)
                <x-report-card :report="$report" />
            @endforeach
        </div>
    </x-container>
</x-layouts.app>
