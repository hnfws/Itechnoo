<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public static function analyzeReport(string $imagePath, string $description): ?array
    {
        // 1. Ambil Semua API Key (Multi-Key Support via koma)
        $rawKeys = env('GEMINI_API_KEYS', env('GEMINI_API_KEY', config('services.gemini.key')));
        $apiKeys = array_filter(array_map('trim', explode(',', $rawKeys)));

        if (empty($apiKeys)) {
            Log::error('Gemini Error: Tidak ada GEMINI_API_KEY yang ditemukan!');
            return self::fallbackResponse();
        }

        // 2. Cek Jalur File Gambar
        $fullPath = storage_path('app/public/' . ltrim($imagePath, '/'));
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/' . ltrim($imagePath, '/'));
        }

        if (!file_exists($fullPath)) {
            Log::error("Gemini Error: File gambar tidak ditemukan di path: {$fullPath}");
            return self::fallbackResponse();
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

        // 3. Prompt Analisis
        $prompt = "Kamu adalah sistem pakar analisis infrastruktur publik pemerintah.
Analisis foto lampiran dan deskripsi dari pelapor berikut secara mendalam:

Deskripsi Pelapor: \"{$description}\"

Tugasmu:
1. Amati foto untuk mengidentifikasi kondisi fisik kerusakan secara nyata.
2. Cocokkan temuan foto dengan deskripsi pelapor.
3. Tentukan tingkat keparahan (severity), urgensi penanganan (urgency), dan potensi risiko bahaya (potential_risk).
4. Tulis analisis untuk masyarakat (ai_masyarakat): Ceritakan apa yang terlihat di foto, tingkat bahaya bagi warga, dan berikan imbauan/saran praktis saat melintas. Gunakan bahasa yang informatif, komunikatif, dan ramah warga.
5. Tulis instruksi teknis untuk admin/dinas (ai_adm): Rekomendasi aksi perbaikan konkret, estimasi alat/bahan, dan skala prioritas.

Kembalikan respon HANYA DALAM FORMAT JSON MURNI TANPA MARKDOWN dengan struktur berikut:
{
  \"severity\": \"Tinggi/Sedang/Rendah\",
  \"urgency\": \"Mendesak/Normal\",
  \"potential_risk\": \"Penjelasan singkat potensi bahaya hasil analisis foto & deskripsi\",
  \"ai_masyarakat\": \"Hasil analisis visual foto & deskripsi khusus untuk warga\",
  \"ai_adm\": \"Instruksi teknis penanganan untuk admin/dinas\"
}";

        // 4. Daftar Model Gemini Melimpah (Mulai dari 3.6 Flash hingga versi Flash stabil)
        $models = [
            config('services.gemini.model', env('GEMINI_MODEL', 'gemini-3.6-flash')),
            'gemini-3.6-flash',
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-2.0-flash-exp',
            'gemini-2.0-flash-lite',
            'gemini-1.5-flash-latest',
            'gemini-1.5-flash-002',
            'gemini-1.5-flash',
        ];

        // Hapus duplikat nama model jika ada
        $models = array_values(array_unique(array_filter($models)));

        try {
            // Acak urutan API Key agar rotasi beban merata
            shuffle($apiKeys);

            foreach ($apiKeys as $apiKey) {
                foreach ($models as $model) {
                    $attempt = 0;
                    $response = null;

                    do {
                        $attempt++;
                        try {
                            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . trim($apiKey);

                            $response = Http::connectTimeout(10)
                                ->timeout(60)
                                ->withHeaders(['Content-Type' => 'application/json'])
                                ->post($endpoint, [
                                    'contents' => [[
                                        'parts' => [
                                            ['text' => $prompt],
                                            ['inline_data' => [
                                                'mime_type' => $mimeType,
                                                'data' => $imageData,
                                            ]],
                                        ],
                                    ]],
                                    'generationConfig' => [
                                        'responseMimeType' => 'application/json',
                                    ],
                                ]);
                        } catch (\Exception $e) {
                            Log::warning("Gemini model {$model} exception (attempt {$attempt}): " . $e->getMessage());
                            $response = null;
                        }

                        // Retry jika terjadi transient server error (500, 502, 503, 504)
                        if ($response && in_array($response->status(), [500, 502, 503, 504], true) && $attempt < 2) {
                            sleep(2);
                        }
                    } while ($response && !$response->successful() && in_array($response->status(), [500, 502, 503, 504], true) && $attempt < 2);

                    // Jika request berhasil, parse hasil JSON
                    if ($response && $response->successful()) {
                        $resultText = $response->json('candidates.0.content.parts.0.text');
                        $cleanJson = preg_replace('/^```json\s*|\s*```$/i', '', trim((string) $resultText));
                        $parsed = json_decode($cleanJson, true);

                        if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                            Log::info("Gemini analysis succeeded using model {$model}.");
                            return $parsed;
                        }
                    }

                    $status = $response?->status() ?? 'no response';
                    Log::warning("Gemini model {$model} failed [{$status}]. Trying next model/key...");

                    // Jika Kena Rate Limit (429), langsung loncat ke API Key berikutnya
                    if ($response && $response->status() === 429) {
                        break;
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error("Gemini API Exception: " . $e->getMessage());
        }

        return self::fallbackResponse();
    }

    private static function fallbackResponse(): array
    {
        return [
            'severity'       => 'Sedang',
            'urgency'        => 'Normal',
            'potential_risk' => 'Potensi risiko sedang di lokasi kejadian, membutuhkan perhatian petugas.',
            'ai_masyarakat'  => 'Laporan Anda telah berhasil kami terima. Silakan berhati-hati saat melintas di area tersebut selagi menunggu verifikasi petugas.',
            'ai_adm'         => 'Laporan ini memerlukan verifikasi dan analisis teknis secara manual oleh petugas dinas.'
        ];
    }
}