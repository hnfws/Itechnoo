<x-layouts.admin title="Laporan">
    @push('styles')
    <style>
        .reports-desktop-table { display: none; }
        .reports-mobile-cards { display: block; }

        @media (min-width: 640px) {
            .reports-desktop-table { display: block; }
            .reports-mobile-cards { display: none; }
        }

        .reports-toolbar {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .reports-filters {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .reports-filters input,
        .reports-filters summary {
            width: 100%;
        }

        @media (min-width: 640px) {
            .reports-toolbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .reports-filters {
                flex-direction: row;
                align-items: center;
            }

            .reports-filters input { width: 12rem; }
            .reports-filters summary { width: auto; }
        }
    </style>
    @endpush

    {{-- Ringkasan atas --}}
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat label="Total Laporan" :value="$stats['total']" class="min-h-32" />
        <x-admin.stat label="Laporan Terverifikasi" :value="$stats['verified']" class="min-h-32" />
        <x-admin.stat label="Laporan Dikerjakan / Dalam Proses" :value="$stats['in_progress']" class="min-h-32" />
        <x-admin.stat label="Laporan Selesai" :value="$stats['done']" class="min-h-32" />
    </div>

    {{-- Panel daftar laporan --}}
    <div class="mt-6 overflow-visible rounded-card border border-line bg-surface shadow-sm">
        <div class="reports-toolbar gap-3 p-5">
            <h2 class="text-base font-semibold text-ink">Laporan Terbaru</h2>

            <form method="GET" action="{{ route('admin.reports') }}" class="reports-filters gap-2">
                <label class="relative">
                    <span class="sr-only">Cari laporan</span>
                    <svg class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M21 21l-4-4" stroke-linecap="round" />
                    </svg>
                    <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search Bar" class="h-10 w-48 rounded-full border border-line bg-surface pr-9 pl-9 text-sm text-ink placeholder:text-ink-muted focus:outline-2 focus:outline-offset-0 focus:outline-brand-600">
                    @if (($search ?? '') !== '')
                        <button type="submit" name="clear_search" value="1" class="absolute top-1/2 right-3 grid size-5 -translate-y-1/2 place-items-center text-lg leading-none text-brand-600 transition hover:text-brand-700" aria-label="Hapus pencarian">
                            &times;
                        </button>
                    @endif
                </label>

                <details class="relative">
                    <summary class="flex h-10 cursor-pointer list-none items-center gap-2 rounded-full border border-line px-4 text-sm font-medium text-ink transition hover:bg-surface-muted focus:outline-2 focus:outline-offset-0 focus:outline-brand-600 [&::-webkit-details-marker]:hidden">
                        {{ $status !== '' ? (\App\Models\Report::STATUSES[$status] ?? 'Filter') : 'Filter' }}
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M4 6h16M6 12h12M9 18h6" stroke-linecap="round" />
                        </svg>
                    </summary>
                    <div class="absolute right-0 z-10 mt-2 min-w-48 rounded-xl border border-line bg-surface p-1 shadow-lg">
                        <button type="submit" name="status" value="" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-ink transition hover:bg-surface-muted">
                            Hapus filter
                        </button>
                        @foreach (\App\Models\Report::STATUSES as $value => $label)
                            <button type="submit" name="status" value="{{ $value }}" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-ink transition hover:bg-surface-muted">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </details>

                <button type="submit" class="sr-only">Terapkan filter</button>
            </form>
        </div>

        <div class="reports-desktop-table overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="border-y border-line bg-surface-muted text-ink-muted">
                        <th scope="col" class="px-5 py-3 font-medium">Judul Laporan</th>
                        <th scope="col" class="px-5 py-3 font-medium">Nama Pelapor</th>
                        <th scope="col" class="px-5 py-3 font-medium">Deskripsi</th>
                        <th scope="col" class="px-5 py-3 font-medium">Lokasi</th>
                        <th scope="col" class="px-5 py-3 font-medium">Skala Prioritas</th>
                        <th scope="col" class="px-5 py-3 font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr class="border-b border-line transition hover:bg-surface-muted">
                            <td class="px-5 py-4 font-medium text-ink">{{ $report['title'] }}</td>
                            <td class="px-5 py-4 text-ink">{{ $report['reporter'] }}</td>
                            <td class="px-5 py-4 text-ink">{{ $report['description'] }}</td>
                            <td class="px-5 py-4 text-ink">
                                @if ($report['maps_url'])
                                    <a href="{{ $report['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-medium text-brand-600 underline hover:text-brand-700">
                                        {{ $report['location'] }}
                                        
                                    </a>
                                @else
                                    {{ $report['location'] }}
                                @endif
                            </td>
                            <td class="px-5 py-4"><x-admin.priority :level="$report['priority']" /></td>
                            <td class="px-5 py-4 font-medium text-green-600">{{ $report['status'] }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.reports.show', $report['id']) }}" class="grid size-9 place-items-center rounded-lg border border-line text-ink-muted transition hover:bg-surface-muted hover:text-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600" aria-label="Buka detail laporan {{ $report['title'] }}">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="reports-mobile-cards min-h-0 divide-y divide-line overflow-visible">
            @foreach ($reports as $report)
                <article class="space-y-3 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs text-ink-muted">Judul Laporan</p>
                            <h3 class="break-words font-semibold text-ink">{{ $report['title'] }}</h3>
                        </div>
                        <a href="{{ route('admin.reports.show', $report['id']) }}" class="grid size-9 shrink-0 place-items-center rounded-lg border border-line text-ink-muted transition hover:bg-surface-muted" aria-label="Buka detail laporan {{ $report['title'] }}">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                            </svg>
                        </a>
                    </div>

                    <dl class="grid gap-2 text-sm">
                        <div>
                            <dt class="text-xs text-ink-muted">Nama Pelapor</dt>
                            <dd class="break-words text-ink">{{ $report['reporter'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-muted">Deskripsi</dt>
                            <dd class="break-words text-ink">{{ $report['description'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-muted">Lokasi</dt>
                            <dd class="break-words">
                                @if ($report['maps_url'])
                                    <a href="{{ $report['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="font-medium text-brand-600 underline">
                                        {{ $report['location'] }}
                                    </a>
                                @else
                                    <span class="text-ink">{{ $report['location'] }}</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <div class="flex flex-wrap items-center gap-2 pt-1 text-sm">
                        <x-admin.priority :level="$report['priority']" />
                        <span class="font-medium text-green-600">{{ $report['status'] }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</x-layouts.admin>