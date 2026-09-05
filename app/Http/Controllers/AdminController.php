<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Report;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Statistik Ringkasan Status Laporan
        $stats = [
            'total'       => Report::count(),
            'high'        => Report::where('priority_score', '>=', 40)->count(),
            'medium'      => Report::whereBetween('priority_score', [20, 39.99])->count(),
            'low'         => Report::where('priority_score', '<', 20)->count(),

            'unverified'  => Report::whereIn('status', ['unverified', 'pending', 'belum diverifikasi', 'belum_diverifikasi'])->count(),
            'verified'    => Report::whereIn('status', ['verified', 'terverifikasi'])->count(),
            'in_progress' => Report::whereIn('status', ['in_progress', 'terverifikasi_in_progress', 'dalam_perbaikan'])->count(),
            'done'        => Report::whereIn('status', ['resolved', 'done', 'selesai'])->count(),
            'rejected'    => Report::whereIn('status', ['rejected', 'ditolak'])->count(),
        ];

        // 2. Data Laporan Keseluruhan
        $reports = Report::latest()->get();

        // 3. Rekap Laporan Bulanan (Tahun Berjalan) untuk Grafik
        $currentYear = date('Y');
        $monthlyReports = Report::select(
                DB::raw('MONTH(created_at) as month'),
                'status',
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month', 'status')
            ->get();

        $chartData = [
            'unverified'  => array_fill(1, 12, 0),
            'verified'    => array_fill(1, 12, 0),
            'in_progress' => array_fill(1, 12, 0),
            'resolved'    => array_fill(1, 12, 0),
            'rejected'    => array_fill(1, 12, 0),
        ];

        // Pemetaan seluruh status ke array grafik bulanan
foreach ($monthlyReports as $item) {
    $m = (int) $item->month;
    $status = strtolower(trim($item->status));

    // Tambahkan 'belum diverifikasi' (dengan spasi) di dalam array
    if (in_array($status, ['unverified', 'pending', 'belum_diverifikasi', 'belum diverifikasi'])) {
        $chartData['unverified'][$m] += $item->total;
    } elseif (in_array($status, ['verified', 'terverifikasi'])) {
        $chartData['verified'][$m] += $item->total;
    } elseif (in_array($status, ['in_progress', 'terverifikasi_in_progress', 'dalam_perbaikan', 'dalam perbaikan'])) {
        $chartData['in_progress'][$m] += $item->total;
    } elseif (in_array($status, ['resolved', 'done', 'selesai'])) {
        $chartData['resolved'][$m] += $item->total;
    } elseif (in_array($status, ['rejected', 'ditolak'])) {
        $chartData['rejected'][$m] += $item->total;
    }
}

        // 4. Ringkasan Otomatis dari AI Gemini
        $aiSummaryData = Cache::remember('daily_ai_summary', now()->addHours(24), function () {
            $latestReports = Report::latest()->take(20)->get(['title', 'location', 'status']);

            if ($latestReports->isEmpty()) {
                return [
                    'content'    => 'Belum ada data laporan yang cukup untuk dirangkum.',
                    'updated_at' => now()->format('H:i') . ' WIB'
                ];
            }

            $apiKey = config('services.gemini_summary.key') ?? config('services.gemini.key');
            $model  = config('services.gemini_summary.model', 'gemini-3.6-flash');

            if (!$apiKey) {
                return [
                    'content'    => 'API Key Gemini belum diisi di file .env / config.',
                    'updated_at' => now()->format('H:i') . ' WIB'
                ];
            }

            $prompt = "Kamu adalah asisten AI Admin Pemerintah. Rangkumkan laporan warga berikut menjadi 3 poin ringkas dalam bahasa Indonesia untuk tindakan hari ini:\n" . json_encode($latestReports);

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => null,
                    'Content-Type'  => 'application/json',
                ])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

            if ($response->failed()) {
                Log::error('Gemini Admin Summary Error: ' . $response->body());
                $errMsg = $response->json('error.message') ?? 'HTTP ' . $response->status();
                return [
                    'content'    => 'Gagal AI Gemini: ' . $errMsg,
                    'updated_at' => now()->format('H:i') . ' WIB'
                ];
            }

            $text = $response->json('candidates.0.content.parts.0.text');

            return [
                'content'    => $text ?? 'Gagal membaca response dari AI Gemini.',
                'updated_at' => now()->format('H:i') . ' WIB'
            ];
        });

        // 5. Kirim data ke View
        return view('admin.dashboard', [
            'adminName'     => auth()->user()->name ?? 'Administrator',
            'stats'         => $stats,
            'reports'       => $reports,
            'chartData'     => $chartData,
            'aiSummary'     => $aiSummaryData['content'],
            'aiSummaryTime' => $aiSummaryData['updated_at'],
        ]);
    }
}