<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('admin.articles.index', [
            'articles' => Article::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.form', ['article' => new Article]);
    }

    public function store(SaveArticleRequest $request): RedirectResponse
    {
        $article = Article::create($this->payload($request));

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Đã tạo bài viết.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(SaveArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($this->payload($request, $article));

        return back()->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Đã xóa bài viết.');
    }

    private function payload(SaveArticleRequest $request, ?Article $article = null): array
    {
        $data = $request->validated();
        unset($data['cover_image']);

        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $article?->id);
        $data['published_at'] = $data['status'] === 'published'
            ? (($data['published_at'] ?? null) ?: now())
            : null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('articles', 'public');

            if ($article?->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }
        }

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (Article::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
