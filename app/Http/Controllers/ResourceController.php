<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Project;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __invoke(): View
    {
        return view('resources', [
            'articles' => Article::published()
                ->latest('published_at')
                ->limit(6)
                ->get(),
            'featuredProjects' => Project::published()
                ->featured()
                ->with('category')
                ->orderBy('sort_order')
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
