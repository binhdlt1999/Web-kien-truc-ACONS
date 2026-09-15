<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['layouts.app', 'home', 'contact'], function ($view): void {
            $settings = Schema::hasTable('settings') ? Setting::publicValues() : [];
            $view->with('siteSettings', $settings);
        });

        View::composer('layouts.app', function ($view): void {
            $navigation = [
                'navServices' => collect(),
                'navCategories' => collect(),
                'navProject' => null,
                'navArticle' => null,
            ];

            if (Schema::hasTable('services')) {
                $navigation['navServices'] = Service::active()
                    ->orderBy('sort_order')
                    ->limit(5)
                    ->get();
            }

            if (Schema::hasTable('categories') && Schema::hasTable('projects')) {
                $navigation['navCategories'] = Category::active()
                    ->withCount(['projects' => fn ($query) => $query->published()])
                    ->orderBy('sort_order')
                    ->limit(6)
                    ->get();

                $navigation['navProject'] = Project::published()
                    ->with('category')
                    ->featured()
                    ->orderBy('sort_order')
                    ->first();
            }

            if (Schema::hasTable('articles')) {
                $navigation['navArticle'] = Article::published()
                    ->latest('published_at')
                    ->first();
            }

            $view->with($navigation);
        });
    }
}
