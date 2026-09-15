<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $contactServices = Service::active()->orderBy('sort_order')->get();

        return view('home', [
            'categories' => Category::active()->orderBy('sort_order')->get(),
            'projects' => Project::published()->featured()->with('category')
                ->orderBy('sort_order')->latest('published_at')->limit(8)->get(),
            'services' => $contactServices->where('is_featured', true)->take(6),
            'contactServices' => $contactServices,
            'testimonials' => Testimonial::query()->where('is_active', true)
                ->orderBy('sort_order')->limit(6)->get(),
            'partners' => Partner::query()->where('is_active', true)
                ->orderBy('sort_order')->get(),
            'articles' => Article::published()->latest('published_at')->limit(3)->get(),
        ]);
    }
}
