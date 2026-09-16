@extends('layouts.app')
@section('title', 'Dịch vụ | ACONS')
@section('meta_description', 'Dịch vụ thiết kế kiến trúc, nội thất, quy hoạch và xây dựng trọn gói của ACONS.')

@php
    $pageContent = static fn (string $key, string $fallback): string => filled($siteSettings[$key] ?? null) ? (string) $siteSettings[$key] : $fallback;
    $serviceDetails = [
        'thiet-ke-kien-truc' => [
            'title' => 'Thiết kế kiến trúc chính xác cho những công trình khác biệt',
            'benefits' => ['Tổ chức công năng theo nhu cầu thực tế', 'Tối ưu thông gió và ánh sáng tự nhiên', 'Ngôn ngữ hình khối nhất quán', 'Phối hợp kiến trúc với kết cấu và kỹ thuật'],
            'deliverables' => ['Phương án mặt bằng và concept', 'Phối cảnh kiến trúc 3D', 'Hồ sơ thiết kế kỹ thuật', 'Hỗ trợ giải đáp trong thi công'],
            'applications' => ['Biệt thự', 'Nhà phố', 'Văn phòng', 'Công trình thương mại'],
        ],
        'thiet-ke-noi-that' => [
            'title' => 'Không gian nội thất gắn với trải nghiệm sống',
            'benefits' => ['Bố trí không gian khoa học', 'Cá nhân hóa theo phong cách sống', 'Kiểm soát màu sắc và vật liệu', 'Cân bằng thẩm mỹ với ngân sách'],
            'deliverables' => ['Mặt bằng bố trí nội thất', 'Phối cảnh 3D từng không gian', 'Bản vẽ chi tiết đồ nội thất', 'Danh mục vật liệu và thiết bị'],
            'applications' => ['Nhà ở', 'Căn hộ', 'Văn phòng', 'Không gian bán lẻ'],
        ],
        'xay-dung-tron-goi' => [
            'title' => 'Một đầu mối xuyên suốt từ thiết kế đến hoàn thiện',
            'benefits' => ['Kiểm soát đồng bộ chất lượng', 'Minh bạch dự toán và tiến độ', 'Giảm xung đột giữa các bộ môn', 'Hạn chế phát sinh trong thi công'],
            'deliverables' => ['Dự toán và kế hoạch triển khai', 'Tổ chức thi công tại hiện trường', 'Giám sát chất lượng theo mốc', 'Nghiệm thu và bàn giao công trình'],
            'applications' => ['Nhà phố', 'Biệt thự', 'Cải tạo', 'Hoàn thiện nội thất'],
        ],
        'quy-hoach' => [
            'title' => 'Tầm nhìn tổng thể cho những không gian phát triển dài hạn',
            'benefits' => ['Nghiên cứu bối cảnh khu đất', 'Tối ưu kết nối và giao thông', 'Phân khu chức năng linh hoạt', 'Định hướng phát triển theo giai đoạn'],
            'deliverables' => ['Phân tích hiện trạng', 'Sơ đồ cấu trúc không gian', 'Thiết kế tổng mặt bằng', 'Hồ sơ định hướng cảnh quan'],
            'applications' => ['Khu dân cư', 'Khu nghỉ dưỡng', 'Tổ hợp thương mại', 'Khuôn viên doanh nghiệp'],
        ],
    ];

    $visualPool = $featuredProjects->map(fn ($project, $index) => $project->cover_image
        ? asset('storage/'.$project->cover_image)
        : ($index % 2 === 0 ? asset('images/hero-architecture.svg') : asset('images/project-placeholder.svg'))
    )->values();

    if ($visualPool->isEmpty()) {
        $visualPool = collect([asset('images/hero-architecture.svg'), asset('images/project-placeholder.svg')]);
    }
@endphp

@section('content')
<article class="services-page">
    <header class="services-hero" style="--services-hero-image: url('{{ asset('images/hero-architecture.svg') }}')" data-reveal="fade">
        <div class="container services-hero-inner">
            <div class="services-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><span>/</span>Dịch vụ</div>
            <div class="services-kicker services-kicker-light">{{ $pageContent('services_hero_kicker', 'Chuyên môn ACONS') }}</div>
            <h1>{{ $pageContent('services_hero_title', 'Giải pháp toàn diện') }}<br><em>{{ $pageContent('services_hero_accent', 'biến ý tưởng thành hiện thực') }}</em></h1>
            <p>{{ $pageContent('services_hero_description', 'Từ kiến trúc, nội thất đến thi công và quy hoạch, ACONS kết nối tư duy thiết kế với năng lực triển khai để tạo nên những công trình có giá trị lâu dài.') }}</p>
            <div class="services-hero-actions">
                <a href="#services-overview" class="btn btn-acons">{{ $pageContent('services_hero_primary_button', 'Khám phá dịch vụ') }} <i class="bi bi-arrow-down"></i></a>
                <a href="{{ route('contacts.create') }}" class="btn btn-outline-figma">{{ $pageContent('services_hero_secondary_button', 'Yêu cầu tư vấn') }}</a>
            </div>
            <div class="services-hero-stats" aria-label="Năng lực dịch vụ ACONS">
                <div><strong>{{ $services->count() }}</strong><span>Nhóm dịch vụ</span></div>
                <div><strong>01</strong><span>Đầu mối xuyên suốt</span></div>
                <div><strong>100%</strong><span>Quy trình đồng bộ</span></div>
            </div>
        </div>
        <a href="#services-overview" class="services-scroll-cue" aria-label="Cuộn đến danh sách dịch vụ"><span>Cuộn xuống</span><i class="bi bi-arrow-down"></i></a>
    </header>

    <section class="services-overview" id="services-overview" data-reveal="fade-up">
        <div class="container">
            <div class="services-heading-split">
                <div>
                    <div class="services-kicker">{{ $pageContent('services_overview_kicker', 'Năng lực chuyên môn') }}</div>
                    <h2>{{ $pageContent('services_overview_title', 'Hệ dịch vụ được tích hợp') }}</h2>
                </div>
                <p>{{ $pageContent('services_overview_description', 'ACONS cung cấp một hệ giải pháp liên kết chặt chẽ, hỗ trợ dự án từ nghiên cứu ban đầu đến thiết kế, triển khai và hoàn thiện.') }}</p>
            </div>

            <div class="services-card-grid">
                @forelse($services as $service)
                    <a href="#service-{{ $service->slug }}" class="services-overview-card">
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <i class="bi {{ $service->icon ?: 'bi-building' }}"></i>
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->summary }}</p>
                        <small>Tìm hiểu thêm <i class="bi bi-arrow-down-right"></i></small>
                    </a>
                @empty
                    <div class="services-empty-state">Dịch vụ đang được cập nhật.</div>
                @endforelse
            </div>
        </div>
    </section>

    @if($services->isNotEmpty())
        <section class="services-deliver-intro" data-reveal="fade-up">
            <div class="container">
                <div class="services-kicker">{{ $pageContent('services_detail_kicker', 'Chi tiết dịch vụ') }}</div>
                <h2>{{ $pageContent('services_detail_title', 'ACONS mang đến điều gì?') }}</h2>
            </div>
        </section>

        <div class="services-delivery-list">
            @foreach($services as $service)
                @php
                    $detail = $serviceDetails[$service->slug] ?? [
                        'title' => $service->name.' được phát triển theo nhu cầu riêng của từng dự án',
                        'benefits' => $service->process_steps ?: ['Khảo sát yêu cầu', 'Phát triển giải pháp', 'Hoàn thiện hồ sơ', 'Hỗ trợ triển khai'],
                        'deliverables' => ['Đề xuất phạm vi công việc', 'Phương án thiết kế', 'Hồ sơ bàn giao', 'Hỗ trợ sau thiết kế'],
                        'applications' => ['Nhà ở', 'Thương mại', 'Văn phòng', 'Không gian chuyên biệt'],
                    ];
                    $serviceImage = $visualPool[$loop->index % $visualPool->count()];
                @endphp
                <section class="services-delivery-row {{ $loop->even ? 'is-reversed' : '' }}" id="service-{{ $service->slug }}" data-reveal="fade-up">
                    <div class="container services-delivery-grid">
                        <figure class="services-delivery-visual">
                            <img src="{{ $serviceImage }}" alt="{{ $service->name }} tại ACONS" loading="lazy">
                            <figcaption><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $service->name }}</figcaption>
                        </figure>
                        <div class="services-delivery-content">
                            <div class="services-kicker">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ $service->name }}</div>
                            <h3>{{ $detail['title'] }}</h3>
                            <p>{{ $service->description ?: $service->summary }}</p>
                            <div class="services-detail-columns">
                                <div>
                                    <h4>Lợi ích chính</h4>
                                    <ul>@foreach($detail['benefits'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                                </div>
                                <div>
                                    <h4>Hồ sơ bàn giao</h4>
                                    <ul>@foreach($detail['deliverables'] as $item)<li>{{ $item }}</li>@endforeach</ul>
                                </div>
                            </div>
                            <div class="services-applications">
                                <h4>Phù hợp với</h4>
                                <div>@foreach($detail['applications'] as $item)<span>{{ $item }}</span>@endforeach</div>
                            </div>
                            <a href="{{ route('contacts.create', ['service' => $service->id]) }}" class="services-detail-link">Yêu cầu tư vấn <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    <section class="services-difference" data-reveal="fade-up">
        <div class="container">
            <div class="services-kicker services-kicker-centered">{{ $pageContent('services_difference_kicker', 'Khác biệt ACONS') }}</div>
            <h2>{{ $pageContent('services_difference_title', 'Vì sao khách hàng chọn ACONS?') }}</h2>
            <div class="services-benefit-grid">
                <article><span>01</span><i class="bi bi-award"></i><h3>Chuyên môn vững vàng</h3><p>Đội ngũ đa chuyên môn hiểu cả tư duy thiết kế lẫn yêu cầu triển khai thực tế.</p></article>
                <article><span>02</span><i class="bi bi-bullseye"></i><h3>Tập trung tính khả thi</h3><p>Mọi giải pháp đều được kiểm chứng bằng công năng, ngân sách và phương án thi công.</p></article>
                <article><span>03</span><i class="bi bi-grid-3x3-gap"></i><h3>Phối hợp đồng bộ</h3><p>Một quy trình thống nhất giúp giảm xung đột và kiểm soát thay đổi trong suốt dự án.</p></article>
                <article><span>04</span><i class="bi bi-lightning-charge"></i><h3>Ứng dụng công nghệ</h3><p>Công cụ trực quan hóa và quản lý hồ sơ giúp quyết định được đưa ra nhanh, chính xác.</p></article>
            </div>
            <div class="services-difference-stats">
                <div><strong>{{ $services->count() }}+</strong><span>Lĩnh vực chuyên môn</span></div>
                <div><strong>100%</strong><span>Quy trình kiểm soát</span></div>
                <div><strong>{{ $featuredProjects->count() }}+</strong><span>Dự án tiêu biểu</span></div>
                <div><strong>01</strong><span>Đội ngũ đồng hành</span></div>
            </div>
        </div>
    </section>

    @if($featuredProjects->isNotEmpty())
        <section class="services-projects" data-reveal="fade-up">
            <div class="container">
                <div class="services-heading-split">
                    <div>
                        <div class="services-kicker">Công trình tiêu biểu</div>
                        <h2>Dự án chọn lọc</h2>
                    </div>
                    <a href="{{ route('projects.index') }}" class="services-text-link">Xem tất cả dự án <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="services-project-grid">
                    @foreach($featuredProjects as $project)
                        @php($projectCover = $project->cover_image ? asset('storage/'.$project->cover_image) : ($loop->odd ? asset('images/hero-architecture.svg') : asset('images/project-placeholder.svg')))
                        <article>
                            <a href="{{ route('projects.show', $project) }}">
                                <img src="{{ $projectCover }}" alt="{{ $project->title }}" loading="lazy">
                                <div><span>{{ $project->category->name }} · {{ $project->location }}</span><h3>{{ $project->title }}</h3></div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="services-process" data-reveal="fade-up">
        <div class="container">
            <div class="services-kicker services-kicker-centered">{{ $pageContent('services_process_kicker', 'Cách chúng tôi làm việc') }}</div>
            <h2>{{ $pageContent('services_process_title', 'Quy trình triển khai') }}</h2>
            <div class="services-process-track">
                @foreach([
                    ['Khám phá', 'Tìm hiểu mục tiêu, bối cảnh và nhu cầu sử dụng'],
                    ['Phân tích', 'Đánh giá hiện trạng, giới hạn và cơ hội của dự án'],
                    ['Phát triển', 'Xây dựng phương án thiết kế và giải pháp tối ưu'],
                    ['Phối hợp', 'Đồng bộ kiến trúc, nội thất và kỹ thuật'],
                    ['Triển khai', 'Hoàn thiện hồ sơ và hỗ trợ tại hiện trường'],
                    ['Bàn giao', 'Nghiệm thu, hoàn thiện và đồng hành vận hành'],
                ] as [$title, $description])
                    <div class="services-process-step">
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $title }}</h3>
                        <p>{{ $description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="services-technology" id="services-technology" data-reveal="fade-up">
        <div class="container">
            <div class="services-technology-grid">
                <div>
                    <div class="services-kicker services-kicker-light">{{ $pageContent('services_technology_kicker', 'Đổi mới') }}</div>
                    <h2>{{ $pageContent('services_technology_title', 'Thiết kế được hỗ trợ bởi công nghệ') }}</h2>
                    <p>{{ $pageContent('services_technology_description', 'ACONS sử dụng công nghệ như một công cụ để kiểm chứng ý tưởng, phối hợp hồ sơ và giúp khách hàng hình dung không gian trước khi thi công.') }}</p>
                </div>
                <div class="services-tech-cards">
                    <article><i class="bi bi-box"></i><h3>Mô hình hóa &amp; BIM</h3><p>Phối hợp mô hình, rà soát xung đột và quản lý thay đổi trong hồ sơ.</p><span>Phối hợp · Kiểm tra · Bàn giao</span></article>
                    <article><i class="bi bi-badge-vr"></i><h3>Trực quan hóa thiết kế</h3><p>Phối cảnh và mô phỏng giúp đánh giá vật liệu, ánh sáng và trải nghiệm không gian.</p><span>3D · Hình ảnh · Trải nghiệm</span></article>
                </div>
            </div>
            <div class="services-tech-stats">
                <div><strong>3D</strong><span>Trực quan hóa không gian</span></div>
                <div><strong>01</strong><span>Nguồn dữ liệu thống nhất</span></div>
                <div><strong>24/7</strong><span>Khả năng truy xuất hồ sơ</span></div>
                <div><strong>100%</strong><span>Thay đổi được kiểm soát</span></div>
            </div>
        </div>
    </section>

    <section class="services-partners" data-reveal="fade">
        <div class="container">
            <div class="services-kicker services-kicker-centered">{{ $pageContent('services_partners_kicker', 'Khách hàng & đối tác') }}</div>
            <h2>{{ $pageContent('services_partners_title', 'Hệ sinh thái đồng hành') }}</h2>
            <div class="services-partner-grid">
                @forelse($partners as $partner)
                    <div>
                        @if($partner->logo)
                            <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy">
                        @else
                            <span>{{ $partner->name }}</span>
                        @endif
                    </div>
                @empty
                    <div><span>Đối tác vật liệu</span></div>
                    <div><span>Đối tác kỹ thuật</span></div>
                @endforelse
                <div><span>Mạng lưới tư vấn</span></div>
                <div><span>Đơn vị thi công</span></div>
                <div><span>Nhà cung cấp</span></div>
                <div><span>Đối tác công nghệ</span></div>
            </div>
        </div>
    </section>

    <section class="services-faq" data-reveal="fade-up">
        <div class="container services-faq-grid">
            <div>
                <div class="services-kicker">{{ $pageContent('services_faq_kicker', 'Câu hỏi thường gặp') }}</div>
                <h2>{{ $pageContent('services_faq_title', 'Bạn đang cần làm rõ điều gì?') }}</h2>
                <p>{{ $pageContent('services_faq_description', 'Trao đổi trực tiếp với đội ngũ ACONS để nhận tư vấn phù hợp với loại hình và giai đoạn dự án của bạn.') }}</p>
                <a href="{{ route('contacts.create') }}" class="btn btn-acons">Liên hệ ngay <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="accordion services-faq-accordion" id="servicesFaqAccordion">
                @foreach([
                    ['Tôi nên liên hệ ACONS ở giai đoạn nào của dự án?', 'Bạn nên trao đổi với ACONS càng sớm càng tốt, lý tưởng nhất là trước khi chốt công năng, ngân sách hoặc bắt đầu hồ sơ xin phép.'],
                    ['ACONS có thể thực hiện đồng thời kiến trúc và nội thất không?', 'Có. Mô hình phối hợp đồng bộ giúp kiến trúc và nội thất thống nhất ngay từ ý tưởng đến chi tiết hoàn thiện.'],
                    ['Dịch vụ xây dựng trọn gói bao gồm những công việc nào?', 'Phạm vi có thể gồm dự toán, chuẩn bị thi công, tổ chức hiện trường, kiểm soát chất lượng, nghiệm thu và bàn giao.'],
                    ['Thời gian thiết kế một dự án thường kéo dài bao lâu?', 'Thời gian phụ thuộc diện tích, loại hình và mức độ phức tạp. ACONS sẽ đề xuất tiến độ cụ thể sau buổi làm việc đầu tiên.'],
                    ['Tôi có thể chỉ thuê ACONS thực hiện một phần công việc không?', 'Có. Phạm vi dịch vụ có thể được thiết kế linh hoạt theo nhu cầu và trạng thái hiện tại của dự án.'],
                ] as [$question, $answer])
                    <div class="accordion-item">
                        <h3 class="accordion-header"><button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#services-faq-{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="services-faq-{{ $loop->iteration }}">{{ $question }}</button></h3>
                        <div id="services-faq-{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#servicesFaqAccordion"><div class="accordion-body">{{ $answer }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="services-final-cta" data-reveal="fade-up">
        <div class="container">
            <div class="services-kicker services-kicker-light">{{ $pageContent('services_cta_kicker', 'Bắt đầu cùng ACONS') }}</div>
            <h2>{{ $pageContent('services_cta_title', 'Sẵn sàng cho dự án tiếp theo?') }}</h2>
            <p>{{ $pageContent('services_cta_description', 'Hãy chia sẻ mục tiêu của bạn để đội ngũ ACONS đề xuất phạm vi dịch vụ phù hợp.') }}</p>
            <div class="services-final-actions">
                <a href="{{ route('contacts.create') }}" class="btn services-cta-light">{{ $pageContent('services_cta_primary_button', 'Nhận tư vấn') }} <i class="bi bi-arrow-right"></i></a>
                <a href="{{ route('projects.index') }}" class="btn services-cta-outline">Xem dự án ACONS</a>
            </div>
        </div>
        <span class="services-cta-mark" aria-hidden="true">A</span>
    </section>
</article>
@endsection
