<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', ['services' => Service::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service]);
    }

    public function store(SaveServiceRequest $request): RedirectResponse
    {
        Service::create($this->payload($request));

        return redirect()->route('admin.services.index')->with('success', 'Đã tạo dịch vụ.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(SaveServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($this->payload($request));

        return back()->with('success', 'Đã cập nhật dịch vụ.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('success', 'Đã xóa dịch vụ.');
    }

    private function payload(SaveServiceRequest $request): array
    {
        $data = $request->validated();
        $service = $request->route('service');
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $service?->id);
        $data['process_steps'] = array_values(array_filter($data['process_steps'] ?? []));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (Service::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
