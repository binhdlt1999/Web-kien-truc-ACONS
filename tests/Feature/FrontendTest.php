<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('ACONS')
            ->assertSee('id="aconsHeroSlider"', false)
            ->assertSee('data-bs-interval="6500"', false)
            ->assertSee('Kiến tạo không gian')
            ->assertSee('Thiết kế đồng bộ')
            ->assertSee('Mỗi công trình');
    }

    public function test_epsilon_page_renders_technology_content(): void
    {
        $this->get(route('epsilon.index'))
            ->assertOk()
            ->assertSee('Công nghệ cho')
            ->assertSee('BIM Coordination');
    }

    public function test_resources_page_renders_knowledge_sections(): void
    {
        $this->get(route('resources.index'))
            ->assertOk()
            ->assertSee('Trung tâm kiến thức ACONS')
            ->assertSee('Hồ sơ dự án');
    }

    public function test_about_page_renders_company_story(): void
    {
        $this->get(route('about.index'))
            ->assertOk()
            ->assertSee('Câu chuyện ACONS')
            ->assertSee('Giá trị cốt lõi');
    }

    public function test_contact_form_validates_and_stores_a_lead(): void
    {
        $service = Service::create([
            'name' => 'Thiết kế kiến trúc',
            'slug' => 'thiet-ke-kien-truc',
            'icon' => 'bi-buildings',
        ]);

        $this->post(route('contacts.store'), [
            'name' => 'Nguyễn Văn An',
            'phone' => '0901 234 567',
            'email' => 'an@example.com',
            'service_id' => $service->id,
            'message' => 'Tôi cần tư vấn thiết kế biệt thự.',
            'website' => '',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $this->assertDatabaseHas(Contact::class, [
            'phone' => '0901 234 567',
            'status' => 'new',
        ]);
    }

    public function test_only_published_projects_are_visible(): void
    {
        $category = Category::create(['name' => 'Biệt thự', 'slug' => 'biet-thu']);
        $published = Project::create([
            'category_id' => $category->id,
            'title' => 'Villa A',
            'slug' => 'villa-a',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        $draft = Project::create([
            'category_id' => $category->id,
            'title' => 'Villa Draft',
            'slug' => 'villa-draft',
            'status' => 'draft',
        ]);

        $this->get(route('projects.show', $published))->assertOk()->assertSee('Villa A');
        $this->get(route('projects.show', $draft))->assertNotFound();
    }
}
