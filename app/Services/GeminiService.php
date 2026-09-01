<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    public function analyzeInfrastructureDamage(string $imagePath, string $userDescription): ?array
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        if (!file_exists($fullPath)) return null;

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath);

        $prompt = "
        Kamu adalah sistem pakar analisis infrastruktur publik pemerintah.
        Tugasmu menganalisis gambar dan deskripsi pengaduan berikut:
        Deskripsi Warga: \"{$userDescription}\"

        Kembalikan output DALAM FORMAT JSON VALID tanpa markdown formatting tambahan.
        Format JSON:
        {
            \"is_infrastructure\": true/false,
            \"ai_summary\": \"Ringkasan singkat masalah dalam 2-3 kalimat\",
            \"ai_safety_advice\": \"Imbauan keselamatan praktis untuk warga sekitar\",
            \"ai_gov_action\": \"Rekomendasi teknis dan alokasi perbaikan untuk admin/pemerintah\",
            \"ai_severity_score\": (integer 1-100 berdasarkan potensi bahaya fisik)
        }
        ";

        try {
            $response = Http::post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $resultText = $response->json('candidates.0.content.parts.0.text');
                return json_decode($resultText, true);
            }
        } catch (\Exception $e) {
            Log::error("Gemini API Error: " . $e->getMessage());
        }

        return null;
    }
}