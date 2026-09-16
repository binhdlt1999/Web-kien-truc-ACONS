<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('sort_order')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial]);
    }

    public function store(SaveTestimonialRequest $request): RedirectResponse
    {
        $testimonial = Testimonial::create($this->payload($request));

        return redirect()->route('admin.testimonials.edit', $testimonial)
            ->with('success', 'Đã thêm đánh giá khách hàng.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(SaveTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->payload($request, $testimonial));

        return back()->with('success', 'Đã cập nhật đánh giá khách hàng.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Đã xóa đánh giá khách hàng.');
    }

    private function payload(SaveTestimonialRequest $request, ?Testimonial $testimonial = null): array
    {
        $data = $request->validated();
        unset($data['photo']);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');

            if ($testimonial?->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
        }

        return $data;
    }
}
