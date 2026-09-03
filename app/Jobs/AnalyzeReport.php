<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\GeminiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(public Report $report)
    {
    }

    public function handle(): void
    {
        $analysis = GeminiService::analyzeReport($this->report->image, $this->report->description);

        if ($analysis === null) {
            throw new \RuntimeException('Gemini belum mengembalikan hasil analisis.');
        }

        $this->report->update([
            'severity' => $analysis['severity'] ?? 'Sedang',
            'urgency' => $analysis['urgency'] ?? 'Normal',
            'potential_risk' => $analysis['potential_risk'] ?? null,
            'ai_masyarakat' => $analysis['ai_masyarakat'] ?? null,
            'ai_adm' => $analysis['ai_adm'] ?? null,
        ]);

        $this->report->recalculatePriorityScore();
    }
}
