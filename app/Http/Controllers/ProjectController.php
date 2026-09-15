<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $projects = Project::published()
            ->with('category')
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $category))
            )
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where(fn ($search) => $search
                ->where('title', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhere('style', 'like', "%{$term}%"))
            )
            ->orderBy('sort_order')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'categories' => Category::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless(
            $project->status === 'published' && $project->published_at?->isPast(),
            404
        );

        $project->increment('view_count');
        $project->load(['category', 'galleryImages', 'floorPlans']);

        $relatedProjects = Project::published()
            ->with('category')
            ->whereKeyNot($project->id)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$project->category_id])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('projects.show', compact('project', 'relatedProjects'));
    }
}
