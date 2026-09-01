<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Feed Pengaduan Publik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen py-8 text-slate-800">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">📢 Feed Laporan Masyarakat</h1>
                <p class="text-sm text-slate-500">Dukung perbaikan infrastruktur publik berdasarkan prioritas AI & warga.</p>
            </div>
            <a href="{{ route('reports.create') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow">
                + Buat Laporan
            </a>
        </div>

        <div class="space-y-6">
            @foreach ($reports as $report)
                @php
                    $hasUpvoted = auth()->check() && $report->upvotes->contains('user_id', auth()->id());
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 flex flex-col md:flex-row gap-5">
                        <div class="w-full md:w-48 h-48 bg-slate-100 rounded-lg overflow-hidden relative flex-shrink-0">
                            <img src="{{ Storage::url($report->image_path) }}" alt="{{ $report->title }}" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-slate-900/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded">
                                📍 {{ Str::limit($report->location_name, 15) }}
                            </span>
                        </div>

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h2 class="font-bold text-lg text-slate-900">{{ $report->title }}</h2>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border bg-slate-50 text-slate-600 border-slate-200">
                                        {{ strtoupper($report->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mb-3">Oleh {{ $report->user->name ?? 'Warga' }} • {{ $report->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-slate-600 mb-4">{{ $report->description }}</p>

                                @if($report->ai_summary)
                                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg mb-4 text-xs">
                                        <div class="font-bold text-emerald-800 mb-1">🤖 Ringkasan AI Gemini (Keparahan: {{ $report->ai_severity_score }}/100)</div>
                                        <p class="text-slate-700 mb-1">{{ $report->ai_summary }}</p>
                                        <p class="text-emerald-900"><strong>⚠️ Imbauan Warga:</strong> {{ $report->ai_safety_advice }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between border-t pt-3 mt-2">
                                <button onclick="toggleUpvote({{ $report->id }})" id="upvote-btn-{{ $report->id }}"
                                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition border
                                               {{ $hasUpvoted ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-300' }}">
                                    <span>▲ {{ $hasUpvoted ? 'Dukungan Anda' : 'Dukung Laporan' }}</span>
                                    <span id="upvote-count-{{ $report->id }}" class="px-2 py-0.5 rounded-full text-[11px] bg-slate-200 text-slate-800">
                                        {{ $report->upvote_count }}
                                    </span>
                                </button>
                                <div class="text-right">
                                    <span class="block text-[10px] text-slate-400">Skor Prioritas</span>
                                    <span id="priority-score-{{ $report->id }}" class="text-sm font-extrabold text-sky-600">
                                        {{ number_format($report->priority_score, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        async function toggleUpvote(reportId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch(`/reports/${reportId}/upvote`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) return alert('Silakan login untuk upvote!');
                const data = await response.json();

                document.getElementById(`upvote-count-${reportId}`).innerText = data.upvote_count;
                document.getElementById(`priority-score-${reportId}`).innerText = parseFloat(data.priority_score).toFixed(1);
            } catch (err) {
                alert('Terjadi kesalahan.');
            }
        }
    </script>
</body>
</html>