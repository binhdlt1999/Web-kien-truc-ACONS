<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_editor_only_shows_active_global_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Cấu hình website')
            ->assertSee('settings[hero_video_url]', false)
            ->assertSee('settings[default_meta_title]', false)
            ->assertSee('settings[google_maps_embed_url]', false)
            ->assertDontSee('settings[hero_title]', false)
            ->assertDontSee('settings[hero_subtitle]', false)
            ->assertDontSee('settings[project_count]', false)
            ->assertDontSee('settings[experience_years]', false)
            ->assertDontSee('settings[team_count]', false);
    }

    public function test_admin_can_update_active_settings_but_not_legacy_keys(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'settings' => [
                    'company_name' => 'ACONS Studio',
                    'default_meta_title' => 'ACONS Studio | Kiến trúc',
                    'default_meta_description' => 'Mô tả SEO mới của ACONS Studio.',
                    'facebook_url' => 'https://www.facebook.com/acons',
                    'hero_title' => 'Trường lỗi thời không được lưu',
                    'project_count' => '999',
                ],
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas(Setting::class, [
            'key' => 'company_name',
            'value' => 'ACONS Studio',
        ]);
        $this->assertDatabaseMissing(Setting::class, ['key' => 'hero_title']);
        $this->assertDatabaseMissing(Setting::class, ['key' => 'project_count']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<title>ACONS Studio | Kiến trúc</title>', false)
            ->assertSee('content="Mô tả SEO mới của ACONS Studio."', false);
    }

    public function test_contact_page_uses_achievement_values_managed_from_homepage(): void
    {
        foreach ([
            'home_stat_1_value' => '18+',
            'home_stat_2_value' => '42+',
            'home_stat_4_value' => '168+',
        ] as $key => $value) {
            Setting::create([
                'key' => $key,
                'value' => $value,
                'group' => 'home',
                'type' => 'text',
                'is_public' => true,
            ]);
        }
        Setting::clearCache();

        $this->get(route('contacts.create'))
            ->assertOk()
            ->assertSee('18+')
            ->assertSee('42+')
            ->assertSee('168+');
    }
}
