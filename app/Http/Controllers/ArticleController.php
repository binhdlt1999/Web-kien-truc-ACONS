<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(Article $article): View
    {
        abort_unless(
            $article->status === 'published' && $article->published_at?->isPast(),
            404
        );

        return view('articles.show', compact('article'));
    }
}
