<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportUpvote;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // 1. Tampilkan Feed Publik Laporan Warga
    public function index()
    {
        $reports = Report::with(['user', 'upvotes'])
            ->orderBy('priority_score', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reports.feed', compact('reports'));
    }

    // 2. Tampilkan Form Buat Laporan
    public function create()
    {
        return view('reports.create');
    }

    // 3. Simpan Laporan Baru + Trigger Gemini AI
    public function store(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'location_name' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $imagePath = $request->file('image')->store('reports', 'public');

        // Panggil Gemini AI Service
        $aiResult = $geminiService->analyzeInfrastructureDamage($imagePath, $request->description);

        if ($aiResult && isset($aiResult['is_infrastructure']) && !$aiResult['is_infrastructure']) {
            return redirect()->back()->withInput()->withErrors([
                'image' => 'Sistem mendeteksi foto bukan merupakan kerusakan infrastruktur publik.'
            ]);
        }

        $report = Report::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'ai_summary' => $aiResult['ai_summary'] ?? 'Laporan diterima dan sedang diverifikasi.',
            'ai_safety_advice' => $aiResult['ai_safety_advice'] ?? 'Tetap waspada di sekitar area.',
            'ai_gov_action' => $aiResult['ai_gov_action'] ?? 'Lakukan evaluasi lapangan.',
            'ai_severity_score' => $aiResult['ai_severity_score'] ?? 50,
        ]);

        $report->recalculatePriorityScore();

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat dan ditinjau AI!');
    }

    // 4. Toggle Upvote Real-Time (AJAX API)
    public function toggleUpvote(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();
        $report = Report::findOrFail($id);

        DB::transaction(function () use ($report, $userId) {
            $existingUpvote = ReportUpvote::where('report_id', $report->id)
                ->where('user_id', $userId)
                ->first();

            if ($existingUpvote) {
                $existingUpvote->delete();
                $report->decrement('upvote_count');
            } else {
                ReportUpvote::create([
                    'report_id' => $report->id,
                    'user_id' => $userId
                ]);
                $report->increment('upvote_count');
            }

            $report->recalculatePriorityScore();
        });

        return response()->json([
            'upvote_count' => $report->fresh()->upvote_count,
            'priority_score' => $report->fresh()->priority_score,
        ]);
    }

    // 5. Dashboard Admin
    public function adminDashboard()
    {
        $reports = Report::with('user')
            ->orderBy('priority_score', 'desc')
            ->paginate(15);

        return view('admin.dashboard', compact('reports'));
    }

    // 6. Update Status oleh Admin
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unverified,verified,in_progress,resolved'
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}