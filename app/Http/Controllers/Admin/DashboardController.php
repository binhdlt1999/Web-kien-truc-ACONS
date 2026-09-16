<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'projectCount' => Project::count(),
            'publishedCount' => Project::published()->count(),
            'newContactCount' => Contact::where('status', 'new')->count(),
            'serviceCount' => Service::where('is_active', true)->count(),
            'testimonialCount' => Testimonial::where('is_active', true)->count(),
            'partnerCount' => Partner::where('is_active', true)->count(),
            'totalViews' => Project::sum('view_count'),
            'latestContacts' => Contact::with('service')->latest()->limit(8)->get(),
            'popularProjects' => Project::with('category')->orderByDesc('view_count')->limit(5)->get(),
        ]);
    }
}
