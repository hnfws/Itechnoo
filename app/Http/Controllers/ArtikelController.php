<?php
namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function publicIndex()
    {
        $articles = Artikel::where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('articles', compact('articles')); // Memanggil view artikel publik
    }

    public function show(Artikel $artikel)
    {
        abort_unless($artikel->status === 'published', 404);

        return view('article-detail', compact('artikel'));
    }
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

    public function edit(Artikel $artikel)
    {
        return view('admin.article-create', ['article' => $artikel]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['content'] = $this->sanitizeContent($validated['content']);

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

    public function update(Request $request, Artikel $artikel)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $this->sanitizeContent($validated['content']),
        ];

        if ($request->hasFile('image')) {
            if ($artikel->image) {
                Storage::disk('public')->delete($artikel->image);
            }

            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $artikel->update($data);

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->image) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil dihapus!');
    }

    private function sanitizeContent(string $content): string
    {
        $content = strip_tags(
            $content,
            '<p><br><strong><b><em><i><u><s><ol><ul><li><h2><h3><blockquote><font><div><span><a>'
        );
        $content = preg_replace('/\s+on\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $content);

        return preg_replace('/(href|src)\s*=\s*([\'\"])\s*javascript:[^\'\"]*\2/i', '$1="#"', $content);
    }
}