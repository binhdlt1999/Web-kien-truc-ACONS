<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact', [
            'services' => Service::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('website');
        $data['ip_address'] = $request->ip();

        Contact::create($data);

        return back()->with('success', 'Cảm ơn bạn! ACONS sẽ liên hệ trong thời gian sớm nhất.');
    }
}
