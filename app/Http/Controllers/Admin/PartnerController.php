<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePartnerRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        return view('admin.partners.index', [
            'partners' => Partner::orderBy('sort_order')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.partners.form', ['partner' => new Partner]);
    }

    public function store(SavePartnerRequest $request): RedirectResponse
    {
        $partner = Partner::create($this->payload($request));

        return redirect()->route('admin.partners.edit', $partner)
            ->with('success', 'Đã thêm đối tác.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.form', compact('partner'));
    }

    public function update(SavePartnerRequest $request, Partner $partner): RedirectResponse
    {
        $partner->update($this->payload($request, $partner));

        return back()->with('success', 'Đã cập nhật đối tác.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Đã xóa đối tác.');
    }

    private function payload(SavePartnerRequest $request, ?Partner $partner = null): array
    {
        $data = $request->validated();
        unset($data['logo']);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');

            if ($partner?->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
        }

        return $data;
    }
}
