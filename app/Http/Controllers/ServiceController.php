<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::active()->orderBy('sort_order')->get(),
            'featuredProjects' => Project::published()
                ->with('category')
                ->orderBy('sort_order')
                ->limit(3)
                ->get(),
            'partners' => Partner::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
