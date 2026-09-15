<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function __invoke(ProjectImage $projectImage): RedirectResponse
    {
        Storage::disk('public')->delete($projectImage->path);
        $projectImage->delete();

        return back()->with('success', 'Đã xóa hình ảnh.');
    }
}
