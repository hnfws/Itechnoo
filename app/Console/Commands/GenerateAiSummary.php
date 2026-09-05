<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GenerateAiSummary extends Command
{
    protected $signature = 'app:generate-ai-summary';
    protected $description = 'Generate ringkasan otomatis laporan infrastruktur';

    public function handle()
    {
        $reports = Report::latest()->take(20)->get(['title', 'location', 'status', 'upvote_count']);

        if ($reports->isEmpty()) {
            return;
        }

        $prompt = "Kamu adalah asisten AI Admin Pemerintah. Rangkumkan laporan warga berikut menjadi 3 poin ringkas dalam bahasa Indonesia untuk tindakan hari ini:\n" 
            . json_encode($reports);

        $apiKey = config('services.gemini.key');
        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        if ($response->successful()) {
            $aiText = $response->json('candidates.0.content.parts.0.text') ?? 'Gagal memproses ringkasan.';

            // SIMPAN KE CACHE SELAMA 24 JAM (86400 DETIK) - TANPA TABEL BARU
            Cache::put('daily_ai_summary', [
                'content' => $aiText,
                'updated_at' => now()->format('H:i') . ' WIB'
            ], 86400);

            $this->info('AI Summary berhasil disimpan ke Cache.');
        }
    }
}