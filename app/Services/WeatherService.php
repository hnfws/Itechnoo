<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    public static function checkExtremeWeather($locationName)
    {
        if (empty($locationName)) {
            return null;
        }

        // Cache hasil API berdasarkan nama lokasi selama 45 menit
        $cacheKey = 'weather_' . md5(strtolower(trim($locationName)));

        return Cache::remember($cacheKey, now()->addMinutes(45), function () use ($locationName) {
            // Gunakan config() sebagai pengganti env()
            $apiKey = config('services.weather.key');

            try {
                $response = Http::timeout(3)->get("https://api.weatherapi.com/v1/current.json", [
                    'key'  => $apiKey,
                    'q'    => $locationName,
                    'lang' => 'id',
                ]);

                if ($response->successful()) {
                    $conditionCode = $response->json('current.condition.code');
                    $conditionText = $response->json('current.condition.text');

                    // Daftar kode cuaca ekstrem (Hujan lebat, Petir, Badai, dll)
                    $extremeCodes = [
                        1087, // Petir
                        1189, 1192, 1195, // Hujan Sedang - Sangat Lebat
                        1243, 1246,       // Hujan Lokal Lebat
                        1273, 1276        // Hujan + Petir
                    ];

                    if (in_array($conditionCode, $extremeCodes)) {
                        return '🌩️ Cuaca Ekstrem: ' . $conditionText;
                    }
                }
            } catch (\Exception $e) {
                return null; // Jika API error/timeout, abaikan agar aplikasi tetap jalan
            }

            return null;
        });
    }
}