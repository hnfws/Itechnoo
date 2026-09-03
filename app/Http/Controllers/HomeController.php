<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Ambil 2 laporan terbaru dari database
        $reports = Report::latest()->take(2)->get();

        return view('home', compact('reports')); // pastikan 'home' sesuai dengan nama file blade kamu (misal: home.blade.php)
    }
}