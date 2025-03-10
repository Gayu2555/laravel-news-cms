<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ReporterController extends Controller
{
    public function index()
    {
        // In a real application, this would be a separate model
        // For now, we'll just filter articles by author type
        $reporterArticles = Article::where('author_type', 'reporter')->get();
        return view('reporters.index', compact('reporterArticles'));
    }
}
