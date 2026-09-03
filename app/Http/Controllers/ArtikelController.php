<?php
namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    // TAMBAHKAN METHOD INDEX INI
    public function index()
    {
        $articles = Artikel::latest()->get(); // Ambil semua artikel terbaru

        return view('admin.articles', compact('articles')); // Sesuaikan dengan nama file Blade daftar artikel kamu
    }

    public function create()
    {
        return view('admin.article-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        Artikel::create([
            'admin_id' => auth()->id() ?? null,
            'title'    => $validated['title'],
            'content'  => $validated['content'],
            'image'    => $imagePath,
            'status'   => 'published',
        ]);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil disimpan!');
    }
}