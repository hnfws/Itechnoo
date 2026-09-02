<!-- <<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Pemerintah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">🏛️ Dashboard Prioritas Infrastruktur</h1>
                <p class="text-slate-400 text-sm">Diurutkan otomatis berdasarkan Algoritma AI + Dukungan Warga</p>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="p-4">Prioritas</th>
                        <th class="p-4">Foto & Laporan</th>
                        <th class="p-4">Rekomendasi AI / Action Plan</th>
                        <th class="p-4">Upvote Warga</th>
                        <th class="p-4">Status & Akses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($reports as $report)
                        <tr class="hover:bg-slate-750">
                            <td class="p-4">
                                <span class="bg-sky-500/10 text-sky-400 border border-sky-500/20 font-black text-lg px-3 py-1 rounded-lg">
                                    {{ number_format($report->priority_score, 1) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-white">{{ $report->title }}</div>
                                <div class="text-xs text-slate-400">📍 {{ $report->location_name }}</div>
                            </td>
                            <td class="p-4 text-xs max-w-md">
                                <div class="text-emerald-400 font-medium mb-1">AI Recommendation:</div>
                                <div class="text-slate-300">{{ $report->ai_gov_action }}</div>
                            </td>
                            <td class="p-4 font-bold text-amber-400">
                                ▲ {{ $report->upvote_count }}
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.reports.status', $report->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-xs rounded-lg p-2 text-white">
                                        <option value="unverified" {{ $report->status == 'unverified' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $report->status == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                        <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
======= -->
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
>>>>>>> 0642a3f41e1b8df3251dfa95f19f46a16e4a6442
