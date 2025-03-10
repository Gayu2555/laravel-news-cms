<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticlePosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'positions'])->orderBy('created_at', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100', // Disesuaikan dengan nama field dari form
            'category' => 'required|exists:categories,id', // Disesuaikan dengan nama field dari form
            'date_created' => 'required|date', // Disesuaikan dengan nama field dari form
            'position' => 'required|in:news_list,sub_headline,headline',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'figcaption' => 'nullable|string', // Untuk keterangan gambar
        ]);

        DB::beginTransaction();

        try {
            // Handle image upload
            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('article_images', 'public')
                : null;

            // Buat artikel (slug otomatis dari Model)
            $article = Article::create([
                'title' => $validated['title'],
                'author_name' => $validated['author'],
                'category_id' => $validated['category'],
                'date_published' => $validated['date_created'],
                'content' => $validated['content'],
                'image_url' => $imagePath,
                'description' => $request->figcaption ?? null,
            ]);

            // Simpan posisi artikel
            ArticlePosition::create([
                'category_id' => $validated['category'],
                'article_id' => $article->id,
                'position' => $validated['position'],
            ]);

            DB::commit();

            // Log sukses untuk debugging
            Log::info('Artikel berhasil disimpan', [
                'article_id' => $article->id,
                'title' => $article->title
            ]);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil dipublikasikan!')
                ->with('debug_info', [
                    'status' => 'success',
                    'message' => 'Artikel berhasil disimpan di database',
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        } catch (\Exception $e) {
            DB::rollback();

            // Log error untuk debugging
            Log::error('Gagal menyimpan artikel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['db_error' => 'Gagal menyimpan artikel: ' . $e->getMessage()])
                ->with('debug_info', [
                    'status' => 'error',
                    'message' => 'Artikel gagal disimpan di database',
                    'error' => $e->getMessage(),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        }
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'category' => 'required|exists:categories,id',
            'date_created' => 'required|date',
            'position' => 'required|in:news_list,sub_headline,headline',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'figcaption' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                if ($article->image_url) {
                    Storage::disk('public')->delete($article->image_url);
                }
                $imagePath = $request->file('image')->store('article_images', 'public');
                $article->image_url = $imagePath;
            }

            // Update artikel
            $article->update([
                'title' => $validated['title'],
                'author_name' => $validated['author'],
                'category_id' => $validated['category'],
                'date_published' => $validated['date_created'],
                'content' => $validated['content'],
                'description' => $request->figcaption ?? null,
            ]);

            // Update posisi artikel
            $article->positions()->updateOrCreate(
                ['article_id' => $article->id],
                [
                    'category_id' => $validated['category'],
                    'position' => $validated['position'],
                ]
            );

            DB::commit();

            // Log sukses untuk debugging
            Log::info('Artikel berhasil diperbarui', [
                'article_id' => $article->id,
                'title' => $article->title
            ]);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil diperbarui!')
                ->with('debug_info', [
                    'status' => 'success',
                    'message' => 'Artikel berhasil diperbarui di database',
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        } catch (\Exception $e) {
            DB::rollback();

            // Log error untuk debugging
            Log::error('Gagal memperbarui artikel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['db_error' => 'Gagal memperbarui artikel: ' . $e->getMessage()])
                ->with('debug_info', [
                    'status' => 'error',
                    'message' => 'Artikel gagal diperbarui di database',
                    'error' => $e->getMessage(),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        }
    }

    public function destroy(Article $article)
    {
        try {
            if ($article->image_url) {
                Storage::disk('public')->delete($article->image_url);
            }

            $article->delete();

            Log::info('Artikel berhasil dihapus', [
                'article_id' => $article->id,
                'title' => $article->title
            ]);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil dihapus!')
                ->with('debug_info', [
                    'status' => 'success',
                    'message' => 'Artikel berhasil dihapus dari database',
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus artikel', [
                'article_id' => $article->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('articles.index')
                ->with('error', 'Gagal menghapus artikel: ' . $e->getMessage())
                ->with('debug_info', [
                    'status' => 'error',
                    'message' => 'Artikel gagal dihapus dari database',
                    'error' => $e->getMessage(),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
        }
    }
}
