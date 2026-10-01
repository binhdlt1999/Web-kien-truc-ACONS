<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const RULES = [
        'company_name' => ['nullable', 'string', 'max:255'],
        'tagline' => ['nullable', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:500'],
        'hotline' => ['nullable', 'string', 'max:30'],
        'email' => ['nullable', 'email', 'max:255'],
        'working_hours' => ['nullable', 'string', 'max:255'],
        'facebook_url' => ['nullable', 'url', 'max:500'],
        'youtube_url' => ['nullable', 'url', 'max:500'],
        'instagram_url' => ['nullable', 'url', 'max:500'],
        'google_maps_embed_url' => ['nullable', 'url', 'max:1000'],
        'default_meta_title' => ['nullable', 'string', 'max:70'],
        'default_meta_description' => ['nullable', 'string', 'max:170'],
        'hero_video_url' => ['nullable', 'url', 'max:1000'],
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = collect(self::RULES)->mapWithKeys(fn ($rule, $key) => ["settings.{$key}" => $rule])->all();
        $rules['logo'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        $validated = $request->validate($rules);

        foreach (array_keys(self::RULES) as $key) {
            if (! array_key_exists($key, $validated['settings'] ?? [])) {
                continue;
            }

            $value = $validated['settings'][$key];
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value === '' ? null : $value,
                'group' => str_contains($key, 'meta') ? 'seo' : 'general',
                'type' => str_contains($key, '_url') ? 'url' : 'text',
                'is_public' => true,
            ]);
        }

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::where('key', 'logo')->value('value');
            $path = $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'logo'], [
                'value' => $path,
                'group' => 'general',
                'type' => 'image',
                'is_public' => true,
            ]);
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        Setting::clearCache();

        return back()->with('success', 'Đã lưu cấu hình hệ thống.');
    }
}
