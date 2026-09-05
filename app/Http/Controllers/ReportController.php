<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportUpvote;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Mengambil/membuat unique key pelapor berbasis cookie browser.
     */
    private function getReporterKey(): string
    {
        $key = Cookie::get('guest_reporter_key');
        if (!$key) {
            $key = (string) Str::uuid();
            Cookie::queue('guest_reporter_key', $key, 525600); // Cookie 1 tahun
        }
        return $key;
    }

    public function index()
    {
        $reports = Report::withCount('upvotes')->latest()->paginate(10);
        return view('reports', compact('reports'));
    }

    public function create()
    {
        // Memanggil file resources/views/report-create.blade.php
        return view('report-create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'reporter'    => 'required|string|max:255',
        'phone'       => 'required|string|max:20',
        'description' => 'required|string',
        'image'       => 'required|image|mimes:jpg,jpeg,png|max:5120',
        'location'    => 'required|string|max:255',
        'latitude'    => 'nullable|numeric|between:-90,90',
        'longitude'   => 'nullable|numeric|between:-180,180',
        'show_name'   => 'nullable', // <--- 1. TAMBAHKAN VALIDASI INI
        'agreement'   => 'accepted',
    ]);

    $reporterKey = $this->getReporterKey();
    $imagePath = $request->file('image')->store('reports', 'public');

    $report = Report::create([
        'reporter_key'   => $reporterKey,
        'title'          => $validated['title'],
        'reporter'       => $validated['reporter'],
        'phone'          => $validated['phone'],
        'description'    => $validated['description'],
        'image'          => $imagePath,
        'location'       => $validated['location'],
        'latitude'       => $validated['latitude'] ?? null,
        'longitude'      => $validated['longitude'] ?? null,
        'show_name'      => $request->has('show_name'), // <--- 2. TAMBAHKAN NATIVE BOOLEAN CONVERSION INI
    ]);

    $aiAnalysis = GeminiService::analyzeReport($imagePath, $validated['description']);
    if ($aiAnalysis !== null) {
        $report->update([
            'severity'       => $aiAnalysis['severity'] ?? 'Sedang',
            'urgency'        => $aiAnalysis['urgency'] ?? 'Normal',
            'potential_risk' => $aiAnalysis['potential_risk'] ?? null,
            'ai_masyarakat'  => $aiAnalysis['ai_masyarakat'] ?? null,
            'ai_adm'         => $aiAnalysis['ai_adm'] ?? null,
        ]);
    }

    $report->recalculatePriorityScore();

    return redirect()->route('reports.show', ['id' => $report->id])
        ->with('success', 'Laporan berhasil terkirim!');
}

    /**
     * Menampilkan Halaman Detail Laporan
     */
    public function show($id)
    {
        $report = Report::withCount('upvotes')->findOrFail($id);
    
    return view('report-detail', compact('report'));
    }

    public function reanalyze($id)
    {
        $report = Report::findOrFail($id);
        $analysis = GeminiService::analyzeReport($report->image, $report->description);

        if ($analysis === null) {
            return back()->with('error', 'Gemini belum merespons. Coba lagi setelah beberapa saat.');
        }

        $report->update([
            'severity'       => $analysis['severity'] ?? 'Sedang',
            'urgency'        => $analysis['urgency'] ?? 'Normal',
            'potential_risk' => $analysis['potential_risk'] ?? null,
            'ai_masyarakat'  => $analysis['ai_masyarakat'] ?? null,
            'ai_adm'         => $analysis['ai_adm'] ?? null,
        ]);
        $report->recalculatePriorityScore();

        return back()->with('success', 'Analisis AI berhasil diperbarui.');
    }

    public function adminShow($id)
{
$report = Report::withCount('upvotes')->findOrFail($id);
    
    return view('admin.report-detail', compact('report'));
}

    /**
     * Logika Upvote Dukungan Warga
     */
    public function toggleUpvote(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $userKey = $this->getReporterKey();

        if ($report->reporter_key === $userKey) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Kamu tidak dapat memberikan upvote pada laporan milik sendiri.',
                ], 422);
            }

            return back()->with('error', 'Kamu tidak dapat memberikan upvote pada laporan milik sendiri.');
        }

        $existingVote = ReportUpvote::where('report_id', $report->id)
            ->where('voter_key', $userKey)
            ->first();

        if (!$existingVote) {
            ReportUpvote::create([
                'report_id' => $report->id,
                'voter_key' => $userKey,
            ]);

            $report->recalculatePriorityScore();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'upvote_count' => $report->upvotes()->count(),
                'has_upvoted' => true,
                'priority_score' => $report->priority_score,
            ]);
        }

        return back()->with('success', $existingVote
            ? 'Kamu sudah mendukung laporan ini.'
            : 'Dukungan berhasil ditambahkan.');
    }

    /**
 * Memperbarui Status Laporan oleh Admin
 */
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:belum diverifikasi,terverifikasi,terverifikasi_in_progress,resolved,rejected',
    ]);

    $report = Report::findOrFail($id);
    $report->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Status laporan berhasil diperbarui!');
}

    public function adminDashboard()
    {
        // 1. Hitung Statistik dari Database
        $stats = [
            'total'       => Report::count(),
            'verified'    => Report::whereIn('status', ['terverifikasi', 'terverifikasi_in_progress', 'resolved'])->count(),
            'in_progress' => Report::where('status', 'terverifikasi_in_progress')->count(),
            'done'        => Report::where('status', 'resolved')->count(),
        ];

        // 2. Ambil & Format Data Laporan
        $reports = Report::latest()->get()->map(function ($report) {
            // Tentukan prioritas berdasarkan score/severity
            $priority = 'rendah';
            if ($report->priority_score >= 80 || strtolower($report->severity) === 'tinggi') {
                $priority = 'tinggi';
            } elseif ($report->priority_score >= 50 || strtolower($report->severity) === 'sedang') {
                $priority = 'menengah';
            }

            // Label Status Human-Readable
            $statusLabel = match ($report->status) {
                'terverifikasi'             => 'Terverifikasi',
                'terverifikasi_in_progress' => 'Proses Penanganan',
                'resolved'                  => 'Selesai',
                'rejected'                  => 'Ditolak',
                default                     => 'Belum Diverifikasi',
            };

            // Link Google Maps dari Latitude & Longitude
            $mapsUrl = null;
            if ($report->latitude && $report->longitude) {
                $mapsUrl = "https://www.google.com/maps?q={$report->latitude},{$report->longitude}";
            }

            return [
           
                'id'       => $report->id,
                'title'    => $report->title,
                'reporter' => $report->reporter,
                'description' => $report->description,
                'location' => $report->location,
                'maps_url' => $mapsUrl,
                'priority' => $priority,
                'status'   => $statusLabel,
            ];
        });

        
        // 3. Kirim Data ke View Blade
        return view('admin.reports', compact('stats', 'reports'));
    }
}