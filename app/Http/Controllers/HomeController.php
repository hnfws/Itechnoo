<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Artikel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        // 1. Ambil SEMUA laporan yang punya koordinat untuk PETA
        $mapReports = Report::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // 2. Ambil 2 laporan terbaru untuk TAMPILAN KARTU/LIST LAPORAN
        $latestReports = Report::latest()->take(2)->get();

        // 3. Ambil 3 artikel terbaru
        $articles = Artikel::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('mapReports', 'latestReports', 'articles'));
    }
}