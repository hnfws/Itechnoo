{{-- 
======================================================================
📌 [VERSI BACKEND / AI INTEGRATION] (DI-KOMEN SEMENTARA)
Kode di bawah ini menggunakan data $reports asli dari controller.
Buka komentar ini nanti saat siap menghubungkan database & Gemini AI.
======================================================================

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
--}}

@php
    // Data fallback jika variabel dari controller tidak terdefinisi
    $adminName ??= 'Administrator';
    $stats ??= [
        'total' => 248,
        'high' => 18,
        'medium' => 63,
        'low' => 167,
        'verified' => 92,
        'in_progress' => 41,
        'done' => 115,
    ];
    $aiSummary ??= 'Belum ada ringkasan AI untuk hari ini.';
    $aiSummaryTime ??= '08:00 WIB';
    $chartData ??= [
        'unverified'  => array_fill(1, 12, 0),
        'verified'    => array_fill(1, 12, 0),
        'in_progress' => array_fill(1, 12, 0),
        'resolved'    => array_fill(1, 12, 0),
        'rejected'    => array_fill(1, 12, 0),
    ];
@endphp

<x-layouts.admin title="Dashboard">
    <p class="text-lg font-semibold text-ink">Welcome, {{ $adminName }}!</p>

    {{-- Peta --}}
    <div id="admin-windy-wrapper" class="relative mt-6 grid min-h-60 overflow-hidden rounded-card bg-surface text-sm font-medium text-ink-muted shadow-sm">
        <div id="windy" class="absolute inset-0"></div>
        <aside class="admin-windy-legend" aria-label="Keterangan warna peta cuaca">
            <p class="text-xs font-semibold">Kecepatan angin</p>
            <p class="mt-0.5 text-[10px] text-white/75">Semakin terang, semakin kencang</p>
            <div class="mt-2 h-2 rounded-full" style="background: linear-gradient(to right, #6271b8, #4a94aa, #4ca44c, #a28740, #8d3f5c, #5f64a0);"></div>
            <div class="mt-1 flex justify-between text-[10px] text-white/80">
                <span>0 kt</span>
                <span>60 kt+</span>
            </div>
            <div class="admin-weather-key-divider"></div>
            <p class="text-xs font-semibold">Keterangan cuaca</p>
            <div class="admin-weather-key-list">
                <span><i class="admin-weather-key-dot bg-violet-500"></i>Badai / petir</span>
                <span><i class="admin-weather-key-dot bg-red-500"></i>Hujan lebat</span>
                <span><i class="admin-weather-key-dot bg-blue-500"></i>Hujan</span>
                <span><i class="admin-weather-key-dot bg-slate-400"></i>Berawan</span>
                <span><i class="admin-weather-key-dot bg-yellow-400"></i>Cerah</span>
            </div>
        </aside>
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

            {{-- 🟢 BAGIAN GRAFIK YANG DIUBAH 🟢 --}}
            <div class="rounded-card border border-line bg-surface p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-ink text-sm">Grafik Rata-rata Laporan</h3>
                        <p class="text-[11px] text-ink-muted">Rekapitulasi status laporan per bulan</p>
                    </div>
                </div>
                <div class="relative h-60 w-full">
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>
            {{-- 🔴 AKHIR BAGIAN GRAFIK YANG DIUBAH 🔴 --}}
        </div>

        {{-- Widget AI Summary --}}
        <div class="flex flex-col justify-between rounded-card border border-line bg-surface p-6 text-sm font-medium text-ink shadow-sm min-h-72">
            <div>
                <div class="flex items-center justify-between border-b border-line pb-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✨</span>
                        <h3 class="font-bold text-ink">AI Summary</h3>
                    </div>
                    <span class="text-[11px] text-ink-muted font-normal bg-surface-muted px-2.5 py-1 rounded-full">
                        {{ $aiSummaryTime }}
                    </span>
                </div>
                
                {{-- Hasil Rangkuman AI Ditampilkan di Sini --}}
                <div class="space-y-2 text-xs leading-relaxed text-ink-muted font-normal">
                    {!! nl2br(e($aiSummary)) !!}
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-line text-[11px] text-ink-muted flex items-center gap-1.5 font-normal">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Diperbarui otomatis setiap jam 08:00 WIB
            </div>
        </div>
    </div>

    {{-- Ringkasan status di bawah --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-3">
        <x-admin.stat label="Laporan Terverifikasi" :value="$stats['verified']" class="min-h-32" />
        <x-admin.stat label="Laporan Dalam Pengerjaan" :value="$stats['in_progress']" class="min-h-32" />
        <x-admin.stat label="Laporan Selesai" :value="$stats['done']" class="min-h-32" />
    </div>

@push('styles')
<style>
    #admin-windy-wrapper .leaflet-marker-pane { z-index: 1000 !important; }
    #admin-windy-wrapper .leaflet-popup-pane { z-index: 1100 !important; }
    #admin-windy-wrapper .leaflet-admin-road-reference-pane-pane { z-index: 450 !important; }
    #admin-windy-wrapper .leaflet-admin-street-pane-pane { z-index: 500 !important; }
    #admin-windy-wrapper #playpause,
    #admin-windy-wrapper #playpause-mobile { display: none !important; }
    .admin-windy-legend {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2000;
        width: 9rem;
        border-radius: 0.5rem;
        background: rgba(0, 0, 0, 0.65);
        padding: 0.75rem;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(8px);
    }
    .admin-weather-key-divider { height: 1px; margin: 10px 0; background: rgba(255, 255, 255, 0.25); }
    .admin-weather-key-list { display: flex; flex-direction: column; gap: 4px; margin-top: 6px; font-size: 10px; line-height: 1; }
    .admin-weather-key-list span { display: inline-flex; align-items: center; gap: 6px; }
    .admin-weather-key-dot { width: 8px; height: 8px; flex: 0 0 8px; border-radius: 9999px; }
    .admin-report-marker { background: transparent !important; border: none !important; }
    .admin-report-location-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: 2px solid #fff;
        border-radius: 9999px;
        background: #dc2626;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        padding: 6px 10px;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------
    // 🟢 BAGIAN GRAFIK CHART.JS YANG DIUBAH 🟢
    // -------------------------------------------------------------
    const canvas = document.getElementById('reportsChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const rawData = @json($chartData);

        const createGradient = (colorStart, colorEnd) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, colorStart);
            gradient.addColorStop(1, colorEnd);
            return gradient;
        };

        const months = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Belum Diverifikasi',
                        data: Object.values(rawData.unverified || {}),
                        borderColor: '#eab308',
                        backgroundColor: createGradient('rgba(234, 179, 8, 0.2)', 'rgba(234, 179, 8, 0.0)'),
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'Terverifikasi',
                        data: Object.values(rawData.verified || {}),
                        borderColor: '#f97316',
                        backgroundColor: createGradient('rgba(249, 115, 22, 0.25)', 'rgba(249, 115, 22, 0.0)'),
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'Dalam Perbaikan',
                        data: Object.values(rawData.in_progress || {}),
                        borderColor: '#06b6d4',
                        backgroundColor: createGradient('rgba(6, 182, 212, 0.25)', 'rgba(6, 182, 212, 0.0)'),
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'Selesai',
                        data: Object.values(rawData.resolved || {}),
                        borderColor: '#3b82f6',
                        backgroundColor: createGradient('rgba(59, 130, 246, 0.35)', 'rgba(59, 130, 246, 0.0)'),
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Ditolak',
                        data: Object.values(rawData.rejected || {}),
                        borderColor: '#ef4444',
                        backgroundColor: createGradient('rgba(239, 68, 68, 0.2)', 'rgba(239, 68, 68, 0.0)'),
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 8,
                            usePointStyle: true,
                            font: { size: 10 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 8,
                        cornerRadius: 6,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // MAP WINDY SCRIPT (TETAP SAMA SEPERTI ASLI)
    // -------------------------------------------------------------
    const reports = @json($reports);
    const options = {
        key: @json(config('services.windy.key')),
        lat: -7.9666204,
        lon: 112.6326321,
        zoom: 7,
        maxZoom: 19,
        graticule: false,
        overlay: 'wind',
        product: 'gfs',
        menu: true,
        message: true,
    };

    windyInit(options, windyAPI => {
        const { map } = windyAPI;
        map.setMaxZoom(19);
        map.createPane('admin-road-reference-pane');
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
            pane: 'admin-road-reference-pane',
            maxZoom: 19,
            maxNativeZoom: 18,
            opacity: 0.9,
            attribution: '&copy; Esri World Transportation'
        }).addTo(map);

        map.createPane('admin-street-pane');
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            pane: 'admin-street-pane',
            maxZoom: 19,
            maxNativeZoom: 19,
            opacity: 0,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const closeZoom = 10;
        const updateMapMode = () => {
            streetMap.setOpacity(map.getZoom() >= closeZoom ? 1 : 0);
        };
        map.on('zoomend', updateMapMode);
        updateMapMode();

        setTimeout(() => document.querySelector('#windy #playpause, #windy #playpause-mobile')?.click(), 500);

        const markers = reports.reduce((items, report) => {
            const lat = parseFloat(report.latitude);
            const lng = parseFloat(report.longitude);
            if (Number.isNaN(lat) || Number.isNaN(lng)) return items;

            const escapeHtml = value => String(value || '').replace(/[&<>"']/g, character => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            }[character]));
            const title = escapeHtml(report.title || 'Laporan');
            const location = escapeHtml(report.location || 'Lokasi laporan');
            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'admin-report-marker',
                    html: `<span class="admin-report-location-tag">📍 ${title}</span>`,
                    iconSize: [120, 30],
                    iconAnchor: [60, 15],
                })
            }).addTo(map).bindPopup(`<strong>${title}</strong><br><span>${location}</span>`);
            items.push(marker);
            return items;
        }, []);

        if (markers.length > 0) {
            map.fitBounds(L.featureGroup(markers).getBounds().pad(0.2));
        }
    });
});
</script>
@endpush

</x-layouts.admin>