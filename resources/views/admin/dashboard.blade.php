@php
    // Data contoh sementara. Nanti backend mengirim angka asli dengan nama variabel yang sama.
    $adminName ??= 'Endministrator';
    $stats ??= [
        'total' => 248,
        'high' => 18,
        'medium' => 63,
        'low' => 167,
        'verified' => 92,
        'in_progress' => 41,
        'done' => 115,
    ];
@endphp

<x-layouts.admin title="Dashboard">
    <p class="text-lg font-semibold text-ink">Welcome, {{ $adminName }}!</p>

    {{-- Peta --}}
    <div class="mt-6 grid min-h-60 place-items-center rounded-card bg-surface text-sm font-medium text-ink-muted shadow-sm">
        Map
    </div>

    {{-- Kolom kiri (statistik + grafik) + kolom kanan (AI Summary) --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="grid gap-6 sm:grid-cols-2">
                <x-admin.stat label="Total Laporan" :value="$stats['total']" class="min-h-40" />
                <x-admin.stat label="Laporan Prioritas Tinggi" :value="$stats['high']" accent="high" class="min-h-40" />
                <x-admin.stat label="Laporan Prioritas Rendah" :value="$stats['low']" accent="low" class="min-h-40" />
                <x-admin.stat label="Laporan Prioritas Menengah" :value="$stats['medium']" accent="medium" class="min-h-40" />
            </div>

            <div class="grid min-h-56 place-items-center rounded-card border border-line bg-surface text-sm font-medium text-ink-muted">
                Grafik rata rata laporan
            </div>
        </div>

        <div class="grid min-h-72 place-items-center rounded-card border border-line bg-surface p-6 text-sm font-medium text-ink-muted">
            AI Summary
        </div>
    </div>

    {{-- Ringkasan status di bawah --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-3">
        <x-admin.stat label="Laporan Terverifikasi" :value="$stats['verified']" class="min-h-32" />
        <x-admin.stat label="Laporan Dalam Pengerjaan" :value="$stats['in_progress']" class="min-h-32" />
        <x-admin.stat label="Laporan Selesai" :value="$stats['done']" class="min-h-32" />
    </div>
</x-layouts.admin>
