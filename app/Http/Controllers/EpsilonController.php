<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class EpsilonController extends Controller
{
    public function __invoke(): View
    {
        return view('epsilon', [
            'featuredProjects' => Project::published()
                ->with('category')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
