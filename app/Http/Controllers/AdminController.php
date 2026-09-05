<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Report;

class AdminController extends Controller
{
    public function index()
    {

$stats = [
    'total'  => Report::count(),
    'high'   => Report::where('priority_score', '>=', 70)->count(),
    'medium' => Report::whereBetween('priority_score', [35, 69.99])->count(),
    'low'    => Report::where('priority_score', '<', 35)->count(),
    // Status Laporan
    'verified'    => Report::where('status', 'terverifikasi')->count(),
    'in_progress' => Report::where('status', 'terverifikasi_in_progress')->count(),
    'done'        => Report::where('status', 'resolved')->count(),
];

        // 2. Data laporan agar Blade tidak error
        $reports = Report::latest()->get();

        // 3. AI Summary dari Cache
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

            // Request ke Google Gemini API (menghapus Authorization header agar tidak 401)
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => null, // Menghapus Bearer token bawaan Laravel
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

        // 4. Kirim ke View
        return view('admin.dashboard', [
            'adminName'     => auth()->user()->name ?? 'Administrator',
            'stats'         => $stats,
            'reports'       => $reports,
            'aiSummary'     => $aiSummaryData['content'],
            'aiSummaryTime' => $aiSummaryData['updated_at'],
        ]);
    }
}