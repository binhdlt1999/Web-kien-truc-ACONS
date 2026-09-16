<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_content_hub_and_homepage_editor(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('Trung tâm nội dung website')
            ->assertSee('Trang chủ');

        $this->actingAs($admin)
            ->get(route('admin.pages.home.edit'))
            ->assertOk()
            ->assertSee('Chỉnh sửa nội dung Trang chủ')
            ->assertSee('Hero slider')
            ->assertSee('Chỉ số năng lực');
    }

    public function test_admin_can_update_homepage_content_without_editing_code(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.pages.home.update'), [
                'content' => [
                    'home_hero_1_title' => 'Kiến tạo giá trị mới',
                    'home_hero_1_description' => 'Nội dung được cập nhật trực tiếp từ CMS.',
                    'home_cta_primary_button' => 'Đặt lịch cùng ACONS',
                ],
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas(Setting::class, [
            'key' => 'home_hero_1_title',
            'value' => 'Kiến tạo giá trị mới',
            'group' => 'home',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Kiến tạo giá trị mới')
            ->assertSee('Nội dung được cập nhật trực tiếp từ CMS.')
            ->assertSee('Đặt lịch cùng ACONS');
    }

    public function test_homepage_content_is_validated_and_escaped(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->from(route('admin.pages.home.edit'))
            ->put(route('admin.pages.home.update'), [
                'content' => ['home_hero_1_title' => str_repeat('a', 121)],
            ])
            ->assertRedirect(route('admin.pages.home.edit'))
            ->assertSessionHasErrors('content.home_hero_1_title');

        $this->actingAs($admin)
            ->put(route('admin.pages.home.update'), [
                'content' => ['home_hero_1_title' => '<script>alert("xss")</script>'],
            ])
            ->assertSessionHasNoErrors();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert("xss")</script>', false);
    }
}
