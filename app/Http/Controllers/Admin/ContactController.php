<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('admin.contacts.index', [
            'contacts' => Contact::with('service')->latest()->paginate(20),
        ]);
    }

    public function show(Contact $contact): View
    {
        return view('admin.contacts.show', ['contact' => $contact->load(['service', 'handler'])]);
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $data = $request->validated();
        $data['handled_by'] = $request->user()->id;
        $data['handled_at'] = $data['status'] === 'new' ? null : now();
        $contact->update($data);

        return back()->with('success', 'Đã cập nhật yêu cầu tư vấn.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa yêu cầu.');
    }
}
