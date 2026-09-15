<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProjectService
{
    public function create(array $data): Project
    {
        $newFiles = $this->storeUploads($data);

        try {
            return DB::transaction(function () use ($data, $newFiles) {
                $attributes = $this->attributes($data);
                $attributes['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title']);
                $attributes['cover_image'] = $newFiles['cover'];

                $project = Project::create($attributes);
                $this->createImages($project, $newFiles['gallery'], 'gallery');
                $this->createImages($project, $newFiles['floor_plans'], 'floor_plan');

                return $project;
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newFiles['all']);
            throw $exception;
        }
    }

    public function update(Project $project, array $data): Project
    {
        $newFiles = $this->storeUploads($data);
        $oldCover = $project->cover_image;

        try {
            DB::transaction(function () use ($project, $data, $newFiles) {
                $attributes = $this->attributes($data);
                $attributes['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title'], $project->id);

                if ($newFiles['cover']) {
                    $attributes['cover_image'] = $newFiles['cover'];
                }

                $project->update($attributes);
                $this->createImages($project, $newFiles['gallery'], 'gallery');
                $this->createImages($project, $newFiles['floor_plans'], 'floor_plan');
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newFiles['all']);
            throw $exception;
        }

        if ($newFiles['cover'] && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        $files = $project->images()->pluck('path')->push($project->cover_image)->filter()->all();

        DB::transaction(function () use ($project) {
            $project->images()->delete();
            $project->delete();
        });

        Storage::disk('public')->delete($files);
    }

    private function attributes(array $data): array
    {
        $attributes = Arr::except($data, ['cover_image', 'gallery', 'floor_plans']);
        $attributes['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $attributes['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($attributes['status'] === 'published' && empty($attributes['published_at'])) {
            $attributes['published_at'] = now();
        }

        if ($attributes['status'] === 'draft') {
            $attributes['published_at'] = null;
        }

        return $attributes;
    }

    private function storeUploads(array $data): array
    {
        $cover = ($data['cover_image'] ?? null) instanceof UploadedFile
            ? $data['cover_image']->store('projects/covers', 'public')
            : null;

        $gallery = collect($data['gallery'] ?? [])
            ->map(fn (UploadedFile $file) => $file->store('projects/gallery', 'public'))
            ->all();

        $floorPlans = collect($data['floor_plans'] ?? [])
            ->map(fn (UploadedFile $file) => $file->store('projects/floor-plans', 'public'))
            ->all();

        return [
            'cover' => $cover,
            'gallery' => $gallery,
            'floor_plans' => $floorPlans,
            'all' => collect([$cover])->merge($gallery)->merge($floorPlans)->filter()->all(),
        ];
    }

    private function createImages(Project $project, array $paths, string $type): void
    {
        foreach ($paths as $index => $path) {
            $project->images()->create([
                'path' => $path,
                'alt_text' => $project->title,
                'type' => $type,
                'sort_order' => $index,
            ]);
        }
    }

    private function uniqueSlug(?string $requestedSlug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($requestedSlug ?: $title) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (Project::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
