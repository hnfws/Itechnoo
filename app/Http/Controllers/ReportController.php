<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportUpvote;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
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
    $reports = Report::where('status', '!=', 'rejected')
        ->withCount('upvotes')
        ->latest()
        ->paginate(10);

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
        $report = Report::where('status', '!=', 'rejected')
            ->withCount('upvotes')
            ->findOrFail($id);
    
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
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Kamu tidak dapat memberikan upvote pada laporan milik sendiri.',
                ], 422);
            }

            return back()->with('error', 'Kamu tidak dapat memberikan upvote pada laporan milik sendiri.');
        }

        $existingVote = ReportUpvote::where('report_id', $report->id)
            ->where('voter_key', $userKey)
            ->first();

        if ($existingVote) {
            $existingVote->delete();
            $hasUpvoted = false;
        } else {
            ReportUpvote::create([
                'report_id' => $report->id,
                'voter_key' => $userKey,
            ]);
            $hasUpvoted = true;
        }

        $report->recalculatePriorityScore();
        $report->refresh();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
            'upvote_count' => ReportUpvote::where('report_id', $report->id)->count(),
                'has_upvoted' => $hasUpvoted,
                'priority_score' => $report->priority_score,
            ]);
        }

        return back()->with('success', $hasUpvoted
            ? 'Dukungan berhasil ditambahkan.'
            : 'Dukungan berhasil dibatalkan.');
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

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return redirect()->route('admin.reports')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    public function adminDashboard(Request $request)
{
    $stats = [
        'total'       => Report::count(),
        'verified'    => Report::whereIn('status', ['terverifikasi', 'terverifikasi_in_progress', 'resolved'])->count(),
        'in_progress' => Report::where('status', 'terverifikasi_in_progress')->count(),
        'done'        => Report::where('status', 'resolved')->count(),
    ];

    $search = $request->boolean('clear_search')
        ? ''
        : trim((string) $request->query('search', ''));
    $status = $request->query('status', '');

    $reportQuery = Report::query()->latest();

    if ($search !== '') {
        $reportQuery->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('reporter', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    if (array_key_exists($status, Report::STATUSES)) {
        $reportQuery->where('status', $status);
    }

    $reports = $reportQuery->get()->map(function ($report) {
        $statusLabel = match ($report->status) {
            'terverifikasi'             => 'Terverifikasi',
            'terverifikasi_in_progress' => 'Proses Penanganan',
            'resolved'                  => 'Selesai',
            'rejected'                  => 'Ditolak',
            default                     => 'Belum Diverifikasi',
        };

        $mapsUrl = null;
        if ($report->latitude && $report->longitude) {
            $mapsUrl = "https://www.google.com/maps?q={$report->latitude},{$report->longitude}";
        }

        return [
            'id'          => $report->id,
            'title'       => $report->title,
            'reporter'    => $report->reporter,
            'description' => $report->description,
            'location'    => $report->location,
            'maps_url'    => $mapsUrl,
            'priority'    => $report->priority_level, // <--- Cukup panggil accessor ini
            'status'      => $statusLabel,
        ];
    });

    return view('admin.reports', compact('stats', 'reports', 'search', 'status'));
}
}