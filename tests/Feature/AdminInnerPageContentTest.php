<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInnerPageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_all_inner_page_editors(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        foreach (['services', 'contact', 'about', 'epsilon', 'resources'] as $page) {
            $this->actingAs($admin)
                ->get(route('admin.pages.edit', $page))
                ->assertOk()
                ->assertSee('Lưu toàn bộ thay đổi')
                ->assertSee('Xem trang public');
        }
    }

    public function test_inner_page_editors_group_related_fields_for_admins(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $expectedGroups = [
            'services' => [
                'services-hero-noi-dung-chinh',
                'services-hero-nut-hanh-dong',
                'services-headings-tong-quan',
                'services-headings-faq',
            ],
            'contact' => [
                'contact-hero-noi-dung-chinh',
                'contact-form-chon-dich-vu',
                'contact-form-bieu-mau',
                'contact-support-vi-tri',
            ],
            'about' => [
                'about-hero-noi-dung-chinh',
                'about-story-noi-dung-chinh',
                'about-identity-gia-tri',
                'about-identity-doi-ngu',
            ],
            'epsilon' => [
                'epsilon-hero-noi-dung-chinh',
                'epsilon-capabilities-noi-dung-chinh',
                'epsilon-capabilities-bim',
                'epsilon-capabilities-ai-lab',
            ],
            'resources' => [
                'resources-hero-noi-dung-chinh',
                'resources-content-bai-viet',
                'resources-content-du-an',
                'resources-guides-tin-tuc',
            ],
        ];

        foreach ($expectedGroups as $page => $groups) {
            $response = $this->actingAs($admin)
                ->get(route('admin.pages.edit', $page))
                ->assertOk();

            foreach ($groups as $group) {
                $response->assertSee('data-page-editor-group="'.$group.'"', false);
            }
        }
    }

    public function test_admin_can_update_every_inner_page_and_public_pages_use_the_saved_content(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $pages = [
            'services' => ['services_hero_title', 'Giải pháp ACONS từ CMS', 'services.index', 'page_services'],
            'contact' => ['contact_hero_title', 'Kết nối ACONS từ CMS', 'contacts.create', 'page_contact'],
            'about' => ['about_hero_title', 'Câu chuyện ACONS từ CMS', 'about.index', 'page_about'],
            'epsilon' => ['epsilon_hero_title', 'Công nghệ ACONS từ CMS', 'epsilon.index', 'page_epsilon'],
            'resources' => ['resources_hero_title', 'Kiến thức ACONS từ CMS', 'resources.index', 'page_resources'],
        ];

        foreach ($pages as $page => [$key, $value, $publicRoute, $group]) {
            $this->actingAs($admin)
                ->put(route('admin.pages.update', $page), [
                    'content' => [$key => $value],
                ])
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $this->assertDatabaseHas(Setting::class, [
                'key' => $key,
                'value' => $value,
                'group' => $group,
                'is_public' => true,
            ]);

            $this->get(route($publicRoute))
                ->assertOk()
                ->assertSee($value);
        }
    }

    public function test_inner_page_content_is_validated_escaped_and_limited_to_known_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->from(route('admin.pages.edit', 'services'))
            ->put(route('admin.pages.update', 'services'), [
                'content' => ['services_hero_title' => str_repeat('a', 161)],
            ])
            ->assertRedirect(route('admin.pages.edit', 'services'))
            ->assertSessionHasErrors('content.services_hero_title');

        $unsafeContent = '<script>alert("xss")</script>';

        $this->actingAs($admin)
            ->put(route('admin.pages.update', 'services'), [
                'content' => [
                    'services_hero_title' => $unsafeContent,
                    'unexpected_database_key' => 'Không được lưu',
                ],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing(Setting::class, ['key' => 'unexpected_database_key']);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
            ->assertDontSee($unsafeContent, false);
    }

    public function test_unknown_page_editor_returns_not_found(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/pages/not-a-page/edit')
            ->assertNotFound();
    }
}
