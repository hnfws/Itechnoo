@php
    // Data contoh sementara. Nanti backend mengirim angka & daftar laporan dengan nama variabel yang sama.
    $stats ??= [
        'total' => 248,
        'verified' => 92,
        'in_progress' => 41,
        'done' => 115,
    ];

    $reports ??= [
        ['id' => 1, 'title' => 'Jalan rusak', 'reporter' => 'Adi Cahyadi', 'location' => 'Jl. Letjen Soeprapto', 'priority' => 'rendah',   'status' => 'Terverifikasi'],
        ['id' => 2, 'title' => 'Jalan rusak', 'reporter' => 'Adi Cahyadi', 'location' => 'Jl. Letjen Soeprapto', 'priority' => 'menengah', 'status' => 'Terverifikasi'],
        ['id' => 3, 'title' => 'Jalan rusak', 'reporter' => 'Adi Cahyadi', 'location' => 'Jl. Letjen Soeprapto', 'priority' => 'rendah',   'status' => 'Terverifikasi'],
        ['id' => 4, 'title' => 'Jalan rusak', 'reporter' => 'Adi Cahyadi', 'location' => 'Jl. Letjen Soeprapto', 'priority' => 'tinggi',   'status' => 'Terverifikasi'],
        ['id' => 5, 'title' => 'Jalan rusak', 'reporter' => 'Adi Cahyadi', 'location' => 'Jl. Letjen Soeprapto', 'priority' => 'rendah',   'status' => 'Terverifikasi'],
    ];
@endphp

<x-layouts.admin title="Laporan">
    {{-- Ringkasan atas --}}
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat label="Total Laporan" :value="$stats['total']" class="min-h-32" />
        <x-admin.stat label="Laporan Terverifikasi" :value="$stats['verified']" class="min-h-32" />
        <x-admin.stat label="Laporan Dikerjakan / Dalam Proses" :value="$stats['in_progress']" class="min-h-32" />
        <x-admin.stat label="Laporan Selesai" :value="$stats['done']" class="min-h-32" />
    </div>

    {{-- Panel daftar laporan --}}
    <div class="mt-6 rounded-card border border-line bg-surface shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 p-5">
            <h2 class="text-base font-semibold text-ink">Recent Reports</h2>

            <div class="flex flex-wrap items-center gap-2">
                <label class="relative">
                    <span class="sr-only">Cari laporan</span>
                    <svg class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M21 21l-4-4" stroke-linecap="round" />
                    </svg>
                    <input type="search" placeholder="Search Bar" class="h-10 w-48 rounded-full border border-line bg-surface pr-4 pl-9 text-sm text-ink placeholder:text-ink-muted focus:outline-2 focus:outline-offset-0 focus:outline-brand-600">
                </label>

                <button type="button" class="flex h-10 items-center gap-2 rounded-full border border-line px-4 text-sm font-medium text-ink transition hover:bg-surface-muted">
                    Status
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M4 6h16M6 12h12M9 18h6" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="border-y border-line bg-surface-muted text-ink-muted">
                        <th scope="col" class="px-5 py-3 font-medium">Judul Laporan</th>
                        <th scope="col" class="px-5 py-3 font-medium">Nama Pelapor</th>
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
                            <td class="px-5 py-4 text-ink">{{ $report['location'] }}</td>
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
    </div>
</x-layouts.admin>
