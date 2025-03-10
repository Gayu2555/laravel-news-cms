<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Menyimpan kategori baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        // Tambahkan slug otomatis
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    // Memperbarui kategori yang sudah ada
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Update slug juga
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Menghapus kategori jika tidak memiliki artikel
    public function destroy(Category $category)
    {
        // Pastikan kategori tidak memiliki artikel sebelum dihapus
        if ($category->articles()->exists()) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category with articles!');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    // API: Mengambil semua kategori dalam format JSON
    public function getCategories()
    {
        $categories = Category::select('id', 'name', 'slug')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
