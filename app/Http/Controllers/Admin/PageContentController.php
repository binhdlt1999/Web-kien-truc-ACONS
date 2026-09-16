<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageContentController extends Controller
{
    /**
     * @var array<int, array{
     *     key: string,
     *     title: string,
     *     description: string,
     *     fields: array<int, array{key: string, label: string, type: string, max: int, fallback: string}>
     * }>
     */
    private const HOME_SECTIONS = [
        [
            'key' => 'hero',
            'title' => 'Hero slider',
            'description' => 'Nội dung ba slide đầu trang. Ảnh nền được lấy từ các dự án nổi bật.',
            'fields' => [
                ['key' => 'home_hero_1_eyebrow', 'label' => 'Slide 1 · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Kiến trúc · Nội thất · Xây dựng'],
                ['key' => 'home_hero_1_title', 'label' => 'Slide 1 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Kiến tạo không gian'],
                ['key' => 'home_hero_1_accent', 'label' => 'Slide 1 · Dòng nhấn màu xanh', 'type' => 'text', 'max' => 120, 'fallback' => 'vượt thời gian'],
                ['key' => 'home_hero_1_description', 'label' => 'Slide 1 · Mô tả', 'type' => 'textarea', 'max' => 500, 'fallback' => 'ACONS đồng hành từ ý tưởng thiết kế đến thi công hoàn thiện, kiến tạo những công trình giàu bản sắc và bền vững.'],
                ['key' => 'home_hero_1_button', 'label' => 'Slide 1 · Chữ trên nút', 'type' => 'text', 'max' => 80, 'fallback' => 'Khám phá dự án'],
                ['key' => 'home_hero_2_eyebrow', 'label' => 'Slide 2 · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Giải pháp toàn diện'],
                ['key' => 'home_hero_2_title', 'label' => 'Slide 2 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Thiết kế đồng bộ'],
                ['key' => 'home_hero_2_accent', 'label' => 'Slide 2 · Dòng nhấn màu xanh', 'type' => 'text', 'max' => 120, 'fallback' => 'thi công chuẩn xác'],
                ['key' => 'home_hero_2_description', 'label' => 'Slide 2 · Mô tả', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Một đội ngũ xuyên suốt từ kiến trúc, nội thất đến kỹ thuật giúp công trình giữ trọn ý tưởng, chất lượng và tiến độ.'],
                ['key' => 'home_hero_2_button', 'label' => 'Slide 2 · Chữ trên nút', 'type' => 'text', 'max' => 80, 'fallback' => 'Xem dịch vụ'],
                ['key' => 'home_hero_3_eyebrow', 'label' => 'Slide 3 · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Dấu ấn ACONS'],
                ['key' => 'home_hero_3_title', 'label' => 'Slide 3 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Mỗi công trình'],
                ['key' => 'home_hero_3_accent', 'label' => 'Slide 3 · Dòng nhấn màu xanh', 'type' => 'text', 'max' => 120, 'fallback' => 'một bản sắc riêng'],
                ['key' => 'home_hero_3_description', 'label' => 'Slide 3 · Mô tả', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Chúng tôi đặt con người và bối cảnh làm trung tâm để mỗi không gian vừa đẹp, vừa bền vững và thực sự thuộc về chủ nhân.'],
                ['key' => 'home_hero_3_button', 'label' => 'Slide 3 · Chữ trên nút', 'type' => 'text', 'max' => 80, 'fallback' => 'Xem hồ sơ năng lực'],
            ],
        ],
        [
            'key' => 'achievements',
            'title' => 'Chỉ số năng lực',
            'description' => 'Năm chỉ số xuất hiện ngay dưới Hero slider.',
            'fields' => [
                ['key' => 'home_stat_1_value', 'label' => 'Chỉ số 1 · Giá trị', 'type' => 'text', 'max' => 30, 'fallback' => '10+'],
                ['key' => 'home_stat_1_label', 'label' => 'Chỉ số 1 · Tiêu đề', 'type' => 'text', 'max' => 100, 'fallback' => 'Năm kinh nghiệm'],
                ['key' => 'home_stat_1_note', 'label' => 'Chỉ số 1 · Ghi chú', 'type' => 'text', 'max' => 160, 'fallback' => 'Kiến tạo giá trị bền vững'],
                ['key' => 'home_stat_2_value', 'label' => 'Chỉ số 2 · Giá trị', 'type' => 'text', 'max' => 30, 'fallback' => '35+'],
                ['key' => 'home_stat_2_label', 'label' => 'Chỉ số 2 · Tiêu đề', 'type' => 'text', 'max' => 100, 'fallback' => 'Nhân sự chuyên môn'],
                ['key' => 'home_stat_2_note', 'label' => 'Chỉ số 2 · Ghi chú', 'type' => 'text', 'max' => 160, 'fallback' => 'Kiến trúc · Nội thất · Kỹ thuật'],
                ['key' => 'home_stat_3_value', 'label' => 'Chỉ số 3 · Giá trị', 'type' => 'text', 'max' => 30, 'fallback' => '1.2M+'],
                ['key' => 'home_stat_3_label', 'label' => 'Chỉ số 3 · Tiêu đề', 'type' => 'text', 'max' => 100, 'fallback' => 'm² thiết kế'],
                ['key' => 'home_stat_3_note', 'label' => 'Chỉ số 3 · Ghi chú', 'type' => 'text', 'max' => 160, 'fallback' => 'Trên nhiều loại hình công trình'],
                ['key' => 'home_stat_4_value', 'label' => 'Chỉ số 4 · Giá trị', 'type' => 'text', 'max' => 30, 'fallback' => '120+'],
                ['key' => 'home_stat_4_label', 'label' => 'Chỉ số 4 · Tiêu đề', 'type' => 'text', 'max' => 100, 'fallback' => 'Dự án bàn giao'],
                ['key' => 'home_stat_4_note', 'label' => 'Chỉ số 4 · Ghi chú', 'type' => 'text', 'max' => 160, 'fallback' => 'Nhà ở · Thương mại · Văn phòng'],
                ['key' => 'home_stat_5_value', 'label' => 'Chỉ số 5 · Giá trị', 'type' => 'text', 'max' => 30, 'fallback' => '100%'],
                ['key' => 'home_stat_5_label', 'label' => 'Chỉ số 5 · Tiêu đề', 'type' => 'text', 'max' => 100, 'fallback' => 'Quy trình kiểm soát'],
                ['key' => 'home_stat_5_note', 'label' => 'Chỉ số 5 · Ghi chú', 'type' => 'text', 'max' => 160, 'fallback' => 'Minh bạch chất lượng và tiến độ'],
            ],
        ],
        [
            'key' => 'section_headings',
            'title' => 'Tiêu đề các section',
            'description' => 'Nhãn, tiêu đề và mô tả dẫn nhập cho từng vùng nội dung Trang chủ.',
            'fields' => [
                ['key' => 'home_services_kicker', 'label' => 'Dịch vụ · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Dịch vụ của chúng tôi'],
                ['key' => 'home_services_title', 'label' => 'Dịch vụ · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Kiến tạo'],
                ['key' => 'home_services_accent', 'label' => 'Dịch vụ · Phần nhấn', 'type' => 'text', 'max' => 120, 'fallback' => 'trọn vẹn'],
                ['key' => 'home_services_description', 'label' => 'Dịch vụ · Mô tả', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Giải pháp đồng bộ từ ý tưởng, thiết kế đến thi công dành cho chủ đầu tư cá nhân và doanh nghiệp.'],
                ['key' => 'home_projects_kicker', 'label' => 'Dự án · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Dự án nổi bật'],
                ['key' => 'home_projects_title', 'label' => 'Dự án · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Công trình'],
                ['key' => 'home_projects_accent', 'label' => 'Dự án · Phần nhấn', 'type' => 'text', 'max' => 120, 'fallback' => 'định hình'],
                ['key' => 'home_projects_line_two', 'label' => 'Dự án · Dòng thứ hai', 'type' => 'text', 'max' => 120, 'fallback' => 'dấu ấn'],
                ['key' => 'home_difference_kicker', 'label' => 'Khác biệt · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Vì sao chọn ACONS'],
                ['key' => 'home_difference_title', 'label' => 'Khác biệt · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Khác biệt'],
                ['key' => 'home_innovation_kicker', 'label' => 'Đổi mới · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Đổi mới trong từng giải pháp'],
                ['key' => 'home_innovation_title', 'label' => 'Đổi mới · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Đổi mới'],
                ['key' => 'home_innovation_description', 'label' => 'Đổi mới · Mô tả', 'type' => 'textarea', 'max' => 700, 'fallback' => 'ACONS ứng dụng công nghệ và quy trình phối hợp liên ngành để rút ngắn thời gian triển khai, đồng thời kiểm soát chất lượng công trình ngay từ giai đoạn thiết kế.'],
                ['key' => 'home_cases_kicker', 'label' => 'Dự án điển hình · Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Nghiên cứu điển hình'],
                ['key' => 'home_cases_title', 'label' => 'Dự án điển hình · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Giải pháp cho'],
                ['key' => 'home_cases_accent', 'label' => 'Dự án điển hình · Phần nhấn', 'type' => 'text', 'max' => 120, 'fallback' => 'những bài toán khó'],
            ],
        ],
        [
            'key' => 'difference',
            'title' => 'Bốn điểm khác biệt',
            'description' => 'Nội dung giải thích lý do khách hàng lựa chọn ACONS.',
            'fields' => [
                ['key' => 'home_difference_1_title', 'label' => 'Điểm 1 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Tư duy thiết kế'],
                ['key' => 'home_difference_1_copy', 'label' => 'Điểm 1 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Mỗi giải pháp bắt đầu từ việc đọc đúng con người, bối cảnh và mục tiêu đầu tư.'],
                ['key' => 'home_difference_2_title', 'label' => 'Điểm 2 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Khả năng thi công'],
                ['key' => 'home_difference_2_copy', 'label' => 'Điểm 2 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Thiết kế luôn được kiểm chứng về tính khả thi, vật liệu, ngân sách và tiến độ.'],
                ['key' => 'home_difference_3_title', 'label' => 'Điểm 3 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Quy trình đồng bộ'],
                ['key' => 'home_difference_3_copy', 'label' => 'Điểm 3 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Kiến trúc, nội thất và kỹ thuật phối hợp xuyên suốt trên một hệ tiêu chuẩn.'],
                ['key' => 'home_difference_4_title', 'label' => 'Điểm 4 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Cam kết chất lượng'],
                ['key' => 'home_difference_4_copy', 'label' => 'Điểm 4 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Từng chi tiết được kiểm soát minh bạch từ hồ sơ đến nghiệm thu công trình.'],
            ],
        ],
        [
            'key' => 'innovation',
            'title' => 'Các năng lực đổi mới',
            'description' => 'Bốn nội dung trong danh sách công nghệ và quy trình.',
            'fields' => [
                ['key' => 'home_innovation_1_title', 'label' => 'Năng lực 1 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Mô hình hóa BIM'],
                ['key' => 'home_innovation_1_copy', 'label' => 'Năng lực 1 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Phối hợp kiến trúc, kết cấu và kỹ thuật trên một mô hình thống nhất.'],
                ['key' => 'home_innovation_2_title', 'label' => 'Năng lực 2 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Thiết kế tối ưu'],
                ['key' => 'home_innovation_2_copy', 'label' => 'Năng lực 2 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Đánh giá công năng, vật liệu và chi phí trước khi bước vào thi công.'],
                ['key' => 'home_innovation_3_title', 'label' => 'Năng lực 3 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Quản lý minh bạch'],
                ['key' => 'home_innovation_3_copy', 'label' => 'Năng lực 3 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Theo dõi đầu việc, tiến độ và tiêu chuẩn nghiệm thu theo từng giai đoạn.'],
                ['key' => 'home_innovation_4_title', 'label' => 'Năng lực 4 · Tiêu đề', 'type' => 'text', 'max' => 120, 'fallback' => 'Giá trị dài hạn'],
                ['key' => 'home_innovation_4_copy', 'label' => 'Năng lực 4 · Nội dung', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Ưu tiên giải pháp phù hợp khí hậu, tiết kiệm năng lượng và dễ bảo trì.'],
            ],
        ],
        [
            'key' => 'contact_cta',
            'title' => 'Khối kêu gọi liên hệ',
            'description' => 'Nội dung CTA hình biểu tượng ACONS ở cuối Trang chủ.',
            'fields' => [
                ['key' => 'home_cta_kicker', 'label' => 'Nhãn nhỏ', 'type' => 'text', 'max' => 120, 'fallback' => 'Bắt đầu một công trình mới'],
                ['key' => 'home_cta_title', 'label' => 'Tiêu đề', 'type' => 'text', 'max' => 160, 'fallback' => 'Cùng ACONS xây dựng'],
                ['key' => 'home_cta_accent', 'label' => 'Dòng nhấn màu xanh', 'type' => 'text', 'max' => 160, 'fallback' => 'không gian tương lai'],
                ['key' => 'home_cta_description', 'label' => 'Mô tả', 'type' => 'textarea', 'max' => 500, 'fallback' => 'Chia sẻ nhu cầu của bạn, đội ngũ ACONS sẽ liên hệ để tư vấn định hướng phù hợp cho công trình.'],
                ['key' => 'home_cta_primary_button', 'label' => 'Nút chính', 'type' => 'text', 'max' => 80, 'fallback' => 'Nhận tư vấn'],
                ['key' => 'home_cta_secondary_button', 'label' => 'Nút phụ', 'type' => 'text', 'max' => 80, 'fallback' => 'Hồ sơ năng lực'],
            ],
        ],
    ];

    public function index(): View
    {
        $managedPages = collect(config('acons_pages'))->map(function (array $page): array {
            $fieldCount = count($this->pageFields($page));
            $customizedCount = Setting::where('group', $page['group'])->whereNotNull('value')->count();

            return [
                'title' => $page['title'],
                'field_count' => $fieldCount,
                'customized_count' => $customizedCount,
            ];
        });

        return view('admin.pages.index', [
            'projectCount' => Project::count(),
            'serviceCount' => Service::count(),
            'articleCount' => Article::count(),
            'testimonialCount' => Testimonial::count(),
            'partnerCount' => Partner::count(),
            'contactCount' => Contact::count(),
            'homeFieldCount' => Setting::where('group', 'home')->whereNotNull('value')->count(),
            'homeFieldTotal' => count($this->homeFields()),
            'managedPages' => $managedPages,
        ]);
    }

    public function editHome(): View
    {
        return view('admin.pages.home', [
            'sections' => self::HOME_SECTIONS,
            'content' => Setting::where('group', 'home')->pluck('value', 'key'),
        ]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        $rules = ['content' => ['required', 'array']];

        foreach ($this->homeFields() as $field) {
            $rules['content.'.$field['key']] = ['nullable', 'string', 'max:'.$field['max']];
        }

        $validated = $request->validate($rules);

        foreach ($this->homeFields() as $field) {
            $value = trim((string) ($validated['content'][$field['key']] ?? ''));

            Setting::updateOrCreate(['key' => $field['key']], [
                'value' => $value !== '' ? $value : null,
                'group' => 'home',
                'type' => $field['type'] === 'textarea' ? 'textarea' : 'text',
                'is_public' => true,
            ]);
        }

        Setting::clearCache();

        return back()->with('success', 'Đã cập nhật nội dung Trang chủ.');
    }

    public function edit(string $page): View
    {
        $definition = $this->pageDefinition($page);

        return view('admin.pages.edit', [
            'pageKey' => $page,
            'pageTitle' => $definition['title'],
            'pageDescription' => $definition['description'],
            'previewRoute' => $definition['preview_route'],
            'sections' => $definition['sections'],
            'content' => Setting::where('group', $definition['group'])->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request, string $page): RedirectResponse
    {
        $definition = $this->pageDefinition($page);
        $fields = $this->pageFields($definition);
        $rules = ['content' => ['required', 'array']];

        foreach ($fields as $field) {
            $rules['content.'.$field['key']] = ['nullable', 'string', 'max:'.$field['max']];
        }

        $validated = $request->validate($rules);

        foreach ($fields as $field) {
            $value = trim((string) ($validated['content'][$field['key']] ?? ''));

            Setting::updateOrCreate(['key' => $field['key']], [
                'value' => $value !== '' ? $value : null,
                'group' => $definition['group'],
                'type' => $field['type'] === 'textarea' ? 'textarea' : 'text',
                'is_public' => true,
            ]);
        }

        Setting::clearCache();

        return back()->with('success', 'Đã cập nhật nội dung '.$definition['title'].'.');
    }

    /**
     * @return array<int, array{key: string, label: string, type: string, max: int, fallback: string}>
     */
    private function homeFields(): array
    {
        return collect(self::HOME_SECTIONS)->pluck('fields')->flatten(1)->all();
    }

    /**
     * @return array{title: string, description: string, preview_route: string, group: string, sections: array<int, array<string, mixed>>}
     */
    private function pageDefinition(string $page): array
    {
        $definition = config('acons_pages.'.$page);

        abort_unless(is_array($definition), 404);

        return $definition;
    }

    /**
     * @param  array{sections: array<int, array<string, mixed>>}  $definition
     * @return array<int, array{key: string, label: string, type: string, max: int, fallback: string}>
     */
    private function pageFields(array $definition): array
    {
        return collect($definition['sections'])->pluck('fields')->flatten(1)->all();
    }
}
