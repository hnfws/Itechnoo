<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $highPriority = Report::query()
            ->where(function ($query) {
                $query->where('priority_score', '>=', 80)
                    ->orWhereRaw('LOWER(severity) IN (?, ?, ?)', ['critical', 'tinggi', 'high']);
            });

        $mediumPriority = Report::query()
            ->whereNot(function ($query) {
                $query->where('priority_score', '>=', 80)
                    ->orWhereRaw('LOWER(severity) IN (?, ?, ?)', ['critical', 'tinggi', 'high']);
            })
            ->where(function ($query) {
                $query->whereBetween('priority_score', [50, 79.99])
                    ->orWhereRaw('LOWER(severity) IN (?, ?)', ['medium', 'sedang']);
            });

        $total = Report::count();
        $highCount = $highPriority->count();
        $mediumCount = $mediumPriority->count();

        $stats = [
            'total'       => $total,
            'high'        => $highCount,
            'medium'      => $mediumCount,
            'low'         => max(0, $total - $highCount - $mediumCount),
            'verified'    => Report::whereIn('status', ['terverifikasi', 'terverifikasi_in_progress', 'resolved'])->count(),
            'in_progress' => Report::where('status', 'terverifikasi_in_progress')->count(),
            'done'        => Report::where('status', 'resolved')->count(),
        ];

        $reportTrends = [
            'hari' => $this->buildTrend(Carbon::now()->startOfDay(), 7, 'day'),
            'bulan' => $this->buildTrend(Carbon::now()->startOfMonth(), 6, 'month'),
            'tahun' => $this->buildTrend(Carbon::now()->startOfYear(), 5, 'year'),
        ];

        $topPriorityReports = Report::query()
            ->orderByDesc('priority_score')
            ->limit(5)
            ->get(['id', 'title', 'priority_score', 'severity', 'status', 'ai_adm']);

        $mapReports = Report::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'title', 'location', 'latitude', 'longitude', 'status', 'priority_score']);

        return view('admin.dashboard', [
            'adminName' => 'Administrator',
            'stats' => $stats,
            'reportTrends' => $reportTrends,
            'topPriorityReports' => $topPriorityReports,
            'mapReports' => $mapReports,
        ]);
    }

    private function buildTrend(Carbon $periodStart, int $periodCount, string $unit): array
    {
        $trend = [];

        for ($offset = $periodCount - 1; $offset >= 0; $offset--) {
            $start = $periodStart->copy()->sub($offset, $unit)->startOf($unit);
            $end = $start->copy()->endOf($unit);

            $trend[] = [
                'label' => match ($unit) {
                    'day' => $start->translatedFormat('d M'),
                    'month' => $start->translatedFormat('M Y'),
                    default => $start->format('Y'),
                },
                'count' => Report::whereBetween('created_at', [$start, $end])->count(),
            ];
        }

        return $trend;
    }
}
