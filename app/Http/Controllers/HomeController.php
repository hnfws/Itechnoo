<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Artikel;


class HomeController extends Controller
{
    public function __invoke()
    {
        // Ambil 2 laporan terbaru dari database
        $reports = Report::latest()->take(2)->get();

        $articles = Artikel::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();


            return view('home', compact('reports', 'articles'));
    }
}