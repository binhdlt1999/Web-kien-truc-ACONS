<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_all_new_content_management_screens(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        foreach ([
            'admin.articles.index',
            'admin.articles.create',
            'admin.testimonials.index',
            'admin.testimonials.create',
            'admin.partners.index',
            'admin.partners.create',
        ] as $routeName) {
            $this->actingAs($admin)->get(route($routeName))->assertOk();
        }
    }

    public function test_admin_can_create_publish_and_delete_an_article_with_cover_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.articles.store'), [
                'title' => 'Giải pháp kiến trúc xanh',
                'slug' => '',
                'excerpt' => 'Một bài viết mới từ ACONS.',
                'body' => "Dòng nội dung thứ nhất.\nDòng nội dung thứ hai.",
                'status' => 'published',
                'published_at' => '',
                'cover_image' => $this->fakePng('cover.png'),
            ])
            ->assertSessionHasNoErrors();

        $article = Article::where('slug', 'giai-phap-kien-truc-xanh')->firstOrFail();

        $this->assertSame('published', $article->status);
        $this->assertNotNull($article->published_at);
        Storage::disk('public')->assertExists($article->cover_image);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Giải pháp kiến trúc xanh')
            ->assertSee('Dòng nội dung thứ nhất.')
            ->assertSee('Dòng nội dung thứ hai.');

        $coverImage = $article->cover_image;

        $this->actingAs($admin)
            ->delete(route('admin.articles.destroy', $article))
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseMissing(Article::class, ['id' => $article->id]);
        Storage::disk('public')->assertMissing($coverImage);
    }

    public function test_admin_can_manage_testimonials_and_partners_shown_on_homepage(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.testimonials.store'), [
                'customer_name' => 'Chị Lan',
                'position' => 'Chủ đầu tư',
                'company' => 'Villa Bình Minh',
                'content' => 'ACONS làm việc rõ ràng và rất chỉn chu.',
                'rating' => 5,
                'photo' => $this->fakePng('customer.png'),
                'is_active' => 1,
                'sort_order' => 1,
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->post(route('admin.partners.store'), [
                'name' => 'Đối tác vật liệu xanh',
                'url' => 'https://example.com',
                'logo' => $this->fakePng('partner.png'),
                'is_active' => 1,
                'sort_order' => 1,
            ])
            ->assertSessionHasNoErrors();

        $testimonial = Testimonial::firstOrFail();
        $partner = Partner::firstOrFail();

        Storage::disk('public')->assertExists($testimonial->photo);
        Storage::disk('public')->assertExists($partner->logo);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('ACONS làm việc rõ ràng và rất chỉn chu.')
            ->assertSee('Chị Lan')
            ->assertSee('Đối tác vật liệu xanh');
    }

    public function test_content_forms_validate_urls_images_and_required_fields(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->from(route('admin.partners.create'))
            ->post(route('admin.partners.store'), [
                'name' => '',
                'url' => 'not-a-url',
                'logo' => UploadedFile::fake()->create('logo.svg', 20, 'image/svg+xml'),
                'is_active' => 1,
                'sort_order' => -1,
            ])
            ->assertRedirect(route('admin.partners.create'))
            ->assertSessionHasErrors(['name', 'url', 'logo', 'sort_order']);
    }

    private function fakePng(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl2nZ8AAAAASUVORK5CYII=', true)
        );
    }
}
