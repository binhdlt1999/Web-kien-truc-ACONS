<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::with('category')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(SaveProjectRequest $request): RedirectResponse
    {
        $project = $this->projectService->create($request->validated());

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Đã tạo dự án thành công.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project->load('images'),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(SaveProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projectService->update($project, $request->validated());

        return back()->with('success', 'Đã cập nhật dự án.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->delete($project);

        return redirect()->route('admin.projects.index')->with('success', 'Đã xóa dự án.');
    }
}
