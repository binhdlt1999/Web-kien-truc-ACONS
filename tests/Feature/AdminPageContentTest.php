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

    public function test_homepage_editor_separates_slides_and_keeps_button_labels_fixed(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Setting::create([
            'key' => 'home_hero_1_button',
            'value' => 'Nhãn nút cũ không được sử dụng',
            'group' => 'home',
            'type' => 'text',
            'is_public' => true,
        ]);
        Setting::clearCache();

        $this->actingAs($admin)
            ->get(route('admin.pages.home.edit'))
            ->assertOk()
            ->assertSee('data-hero-slide-editor="1"', false)
            ->assertSee('data-hero-slide-editor="2"', false)
            ->assertSee('data-hero-slide-editor="3"', false)
            ->assertDontSee('Slide 1 · Chữ trên nút')
            ->assertDontSee('Slide 2 · Chữ trên nút')
            ->assertDontSee('Slide 3 · Chữ trên nút');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Khám phá dự án')
            ->assertSee('Xem dịch vụ')
            ->assertSee('Xem hồ sơ năng lực')
            ->assertDontSee('Nhãn nút cũ không được sử dụng');
    }

    public function test_homepage_editor_groups_each_achievement_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.pages.home.edit'))
            ->assertOk();

        foreach (range(1, 5) as $statNumber) {
            $response
                ->assertSee('data-achievement-editor="'.$statNumber.'"', false)
                ->assertSee('Chỉ số '.$statNumber);
        }

        $response
            ->assertDontSee('Chỉ số 1 · Giá trị')
            ->assertDontSee('Chỉ số 1 · Tiêu đề')
            ->assertDontSee('Chỉ số 1 · Ghi chú');
    }

    public function test_homepage_editor_groups_the_remaining_content_sections(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.pages.home.edit'))
            ->assertOk();

        foreach (['services', 'projects', 'difference', 'innovation', 'cases'] as $group) {
            $response->assertSee('data-section-heading-editor="'.$group.'"', false);
        }

        foreach (range(1, 4) as $itemNumber) {
            $response
                ->assertSee('data-difference-editor="'.$itemNumber.'"', false)
                ->assertSee('data-innovation-editor="'.$itemNumber.'"', false);
        }

        $response
            ->assertSee('data-contact-cta-editor="content"', false)
            ->assertSee('data-contact-cta-editor="buttons"', false)
            ->assertSee('Nội dung chính')
            ->assertSee('Nút hành động')
            ->assertDontSee('Điểm 1 · Tiêu đề')
            ->assertDontSee('Năng lực 1 · Tiêu đề')
            ->assertDontSee('Dịch vụ · Nhãn nhỏ');
    }
}
