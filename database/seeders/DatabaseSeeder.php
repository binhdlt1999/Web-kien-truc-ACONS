<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = config('acons.admin_email');
        $adminPassword = config('acons.admin_password');

        if ($adminEmail && $adminPassword) {
            User::updateOrCreate(['email' => $adminEmail], [
                'name' => 'ACONS Administrator',
                'password' => $adminPassword,
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
        } else {
            $this->command?->warn('Chưa tạo admin: hãy đặt ACONS_ADMIN_EMAIL và ACONS_ADMIN_PASSWORD trong .env rồi chạy lại db:seed.');
        }

        $categories = collect([
            ['name' => 'Biệt thự', 'slug' => 'biet-thu'],
            ['name' => 'Nhà phố', 'slug' => 'nha-pho'],
            ['name' => 'Thương mại', 'slug' => 'thuong-mai'],
            ['name' => 'Nội thất', 'slug' => 'noi-that'],
        ])->mapWithKeys(function ($item, $index) {
            $category = Category::updateOrCreate(['slug' => $item['slug']], $item + ['sort_order' => $index]);

            return [$item['slug'] => $category];
        });

        $services = [
            ['name' => 'Thiết kế kiến trúc', 'slug' => 'thiet-ke-kien-truc', 'icon' => 'bi-buildings', 'summary' => 'Giải pháp kiến trúc hài hòa công năng, thẩm mỹ và bối cảnh.', 'process_steps' => ['Khảo sát và tiếp nhận yêu cầu', 'Phát triển ý tưởng', 'Hoàn thiện hồ sơ kỹ thuật', 'Hỗ trợ triển khai'], 'sort_order' => 1],
            ['name' => 'Thiết kế nội thất', 'slug' => 'thiet-ke-noi-that', 'icon' => 'bi-lamp', 'summary' => 'Không gian nội thất nhất quán với phong cách sống của gia chủ.', 'process_steps' => ['Khảo sát hiện trạng', 'Bố trí công năng', 'Thiết kế 3D', 'Hồ sơ thi công'], 'sort_order' => 2],
            ['name' => 'Xây dựng trọn gói', 'slug' => 'xay-dung-tron-goi', 'icon' => 'bi-bricks', 'summary' => 'Kiểm soát đồng bộ chất lượng, tiến độ và ngân sách công trình.', 'process_steps' => ['Dự toán', 'Chuẩn bị thi công', 'Thi công và giám sát', 'Nghiệm thu bàn giao'], 'sort_order' => 3],
            ['name' => 'Quy hoạch', 'slug' => 'quy-hoach', 'icon' => 'bi-map', 'summary' => 'Tổ chức không gian tổng thể hiệu quả và có tầm nhìn dài hạn.', 'process_steps' => ['Nghiên cứu bối cảnh', 'Xây dựng kịch bản', 'Thiết kế tổng mặt bằng', 'Hoàn thiện hồ sơ'], 'sort_order' => 4],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service + [
                'description' => $service['summary'],
                'is_featured' => true,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['title' => 'Villa An Nhiên', 'slug' => 'villa-an-nhien', 'category' => 'biet-thu', 'location' => 'Đà Nẵng', 'area' => 420, 'style' => 'Hiện đại'],
            ['title' => 'Townhouse Mộc', 'slug' => 'townhouse-moc', 'category' => 'nha-pho', 'location' => 'TP. Hồ Chí Minh', 'area' => 180, 'style' => 'Tối giản'],
            ['title' => 'ACONS Creative Office', 'slug' => 'acons-creative-office', 'category' => 'thuong-mai', 'location' => 'Hà Nội', 'area' => 650, 'style' => 'Đương đại'],
            ['title' => 'Residence Lumière', 'slug' => 'residence-lumiere', 'category' => 'noi-that', 'location' => 'Nha Trang', 'area' => 210, 'style' => 'Modern Luxury'],
        ] as $index => $data) {
            Project::updateOrCreate(['slug' => $data['slug']], [
                'category_id' => $categories[$data['category']]->id,
                'title' => $data['title'],
                'location' => $data['location'],
                'area' => $data['area'],
                'completion_year' => now()->year,
                'style' => $data['style'],
                'summary' => 'Một dự án chọn lọc của ACONS, đề cao trải nghiệm sống và giá trị bền vững.',
                'description' => 'Công trình được phát triển từ nghiên cứu kỹ lưỡng về bối cảnh, ánh sáng và nhu cầu sử dụng. Các không gian được kết nối linh hoạt nhưng vẫn đảm bảo sự riêng tư cần thiết.',
                'design_concept' => 'Ngôn ngữ hình khối tinh giản kết hợp vật liệu tự nhiên tạo nên một tổng thể hiện đại, ấm áp và vượt thời gian.',
                'construction_solution' => 'Giải pháp kết cấu, vật liệu và kỹ thuật được phối hợp đồng bộ nhằm kiểm soát chất lượng, tiến độ và chi phí.',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => $index,
                'published_at' => now()->subDays($index),
            ]);
        }

        foreach ([
            'company_name' => 'ACONS',
            'tagline' => 'Kiến tạo không gian sống hiện đại, bền vững và giàu bản sắc.',
            'hotline' => '0900 000 000',
            'email' => 'hello@acons.vn',
            'address' => 'Văn phòng ACONS, Việt Nam',
            'working_hours' => 'Thứ 2 – Thứ 7, 08:00 – 17:30',
            'hero_title' => 'Kiến tạo không gian sống vượt thời gian',
            'hero_subtitle' => 'ACONS đồng hành từ ý tưởng thiết kế đến thi công hoàn thiện.',
            'project_count' => '120',
            'experience_years' => '10',
            'team_count' => '35',
            'default_meta_title' => 'ACONS | Kiến trúc & Xây dựng',
            'default_meta_description' => 'ACONS cung cấp dịch vụ thiết kế kiến trúc, nội thất và xây dựng trọn gói.',
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'general', 'type' => 'text', 'is_public' => true]);
        }

        Testimonial::updateOrCreate(['customer_name' => 'Anh Minh'], ['company' => 'Chủ đầu tư Villa An Nhiên', 'content' => 'ACONS lắng nghe kỹ và kiểm soát công trình rất sát. Không gian hoàn thiện đúng tinh thần gia đình mong muốn.', 'rating' => 5, 'is_active' => true]);
        Partner::updateOrCreate(['name' => 'Đối tác vật liệu A'], ['is_active' => true, 'sort_order' => 1]);
        Partner::updateOrCreate(['name' => 'Đối tác nội thất B'], ['is_active' => true, 'sort_order' => 2]);

        Article::updateOrCreate(['slug' => '5-nguyen-tac-thiet-ke-nha-o-ben-vung'], [
            'title' => '5 nguyên tắc thiết kế nhà ở bền vững',
            'excerpt' => 'Những quyết định nền tảng giúp công trình thích nghi tốt với khí hậu và sử dụng lâu dài.',
            'body' => 'Thiết kế bền vững bắt đầu từ việc đọc đúng bối cảnh khu đất, tận dụng thông gió và ánh sáng tự nhiên, lựa chọn vật liệu phù hợp, tổ chức không gian linh hoạt và tính toán vòng đời vận hành.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Setting::clearCache();
    }
}
