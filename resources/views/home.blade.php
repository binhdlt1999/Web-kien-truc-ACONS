@extends('layouts.app')

@section('title', 'ACONS | Kiến trúc và Xây dựng')

@php
    $homeContent = static function (string $key, string $fallback) use ($siteSettings): string {
        $value = $siteSettings[$key] ?? null;

        return filled($value) ? (string) $value : $fallback;
    };
    $homepageProjects = $projects->take(4)->values();
    $heroProjects = $projects->filter(fn ($project) => filled($project->cover_image))->take(3)->values();
    $heroFallbackImages = [
        asset('images/hero-architecture.svg'),
        asset('images/project-placeholder.svg'),
        asset('images/hero-architecture.svg'),
    ];
    $heroSlides = collect([
        [
            'eyebrow' => $homeContent('home_hero_1_eyebrow', 'Kiến trúc · Nội thất · Xây dựng'),
            'title' => $homeContent('home_hero_1_title', 'Kiến tạo không gian'),
            'accent' => $homeContent('home_hero_1_accent', 'vượt thời gian'),
            'description' => $homeContent('home_hero_1_description', $siteSettings['hero_subtitle'] ?? 'ACONS đồng hành từ ý tưởng thiết kế đến thi công hoàn thiện, kiến tạo những công trình giàu bản sắc và bền vững.'),
            'primary_label' => 'Khám phá dự án',
            'primary_url' => route('projects.index'),
        ],
        [
            'eyebrow' => $homeContent('home_hero_2_eyebrow', 'Giải pháp toàn diện'),
            'title' => $homeContent('home_hero_2_title', 'Thiết kế đồng bộ'),
            'accent' => $homeContent('home_hero_2_accent', 'thi công chuẩn xác'),
            'description' => $homeContent('home_hero_2_description', 'Một đội ngũ xuyên suốt từ kiến trúc, nội thất đến kỹ thuật giúp công trình giữ trọn ý tưởng, chất lượng và tiến độ.'),
            'primary_label' => 'Xem dịch vụ',
            'primary_url' => route('services.index'),
        ],
        [
            'eyebrow' => $homeContent('home_hero_3_eyebrow', 'Dấu ấn ACONS'),
            'title' => $homeContent('home_hero_3_title', 'Mỗi công trình'),
            'accent' => $homeContent('home_hero_3_accent', 'một bản sắc riêng'),
            'description' => $homeContent('home_hero_3_description', 'Chúng tôi đặt con người và bối cảnh làm trung tâm để mỗi không gian vừa đẹp, vừa bền vững và thực sự thuộc về chủ nhân.'),
            'primary_label' => 'Xem hồ sơ năng lực',
            'primary_url' => route('about.index'),
        ],
    ])->map(function (array $slide, int $index) use ($heroProjects, $heroFallbackImages): array {
        $project = $heroProjects->get($index);
        $slide['image'] = $project?->cover_image
            ? asset('storage/'.$project->cover_image)
            : $heroFallbackImages[$index];
        $slide['image_alt'] = $project?->title
            ? 'Dự án '.$project->title.' của ACONS'
            : 'Không gian kiến trúc do ACONS thiết kế';

        return $slide;
    });
    $heroProject = $heroProjects->first();
    $innovationProject = $projects->first(fn ($project) => filled($project->cover_image) && $project->isNot($heroProject));
    $innovationImage = $innovationProject?->cover_image
        ? asset('storage/'.$innovationProject->cover_image)
        : asset('images/project-placeholder.svg');
@endphp

@section('content')
<section id="aconsHeroSlider" class="carousel slide carousel-fade home-hero is-progressing" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover" data-bs-touch="true" aria-label="Giới thiệu ACONS">
    <div class="carousel-inner">
        @foreach($heroSlides as $slide)
            <article class="carousel-item home-hero-slide {{ $loop->first ? 'active is-animated' : '' }}" data-bs-interval="6500">
                <img
                    class="home-hero-media"
                    src="{{ $slide['image'] }}"
                    alt="{{ $slide['image_alt'] }}"
                    decoding="async"
                    @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                >
                @if($loop->first && !empty($siteSettings['hero_video_url']))
                    <video class="home-hero-video" autoplay muted loop playsinline preload="metadata" poster="{{ $slide['image'] }}" aria-hidden="true">
                        <source src="{{ $siteSettings['hero_video_url'] }}" type="video/mp4">
                    </video>
                @endif
                <span class="home-hero-overlay" aria-hidden="true"></span>

                <div class="container home-hero-slide-inner">
                    <div class="home-hero-content">
                        <div class="home-kicker home-kicker-light home-hero-animate home-hero-eyebrow">{{ $slide['eyebrow'] }}</div>
                        @if($loop->first)
                            <h1 class="home-hero-title home-hero-animate">
                                <span>{{ $slide['title'] }}</span>
                                <span class="text-sky">{{ $slide['accent'] }}</span>
                            </h1>
                        @else
                            <h2 class="home-hero-title home-hero-animate">
                                <span>{{ $slide['title'] }}</span>
                                <span class="text-sky">{{ $slide['accent'] }}</span>
                            </h2>
                        @endif
                        <p class="home-hero-copy home-hero-animate">{{ $slide['description'] }}</p>
                        <div class="home-hero-actions home-hero-animate">
                            <a class="btn btn-acons" href="{{ $slide['primary_url'] }}">{{ $slide['primary_label'] }} <i class="bi bi-arrow-up-right"></i></a>
                            <a class="home-text-link home-text-link-light" href="{{ route('contacts.create') }}">Trao đổi cùng ACONS <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="home-hero-navigation">
        <div class="container home-hero-navigation-inner">
            <div class="carousel-indicators home-hero-indicators" aria-label="Chọn nội dung giới thiệu">
                @foreach($heroSlides as $slide)
                    <button
                        type="button"
                        data-bs-target="#aconsHeroSlider"
                        data-bs-slide-to="{{ $loop->index }}"
                        class="{{ $loop->first ? 'active' : '' }}"
                        @if($loop->first) aria-current="true" @endif
                        aria-label="Xem slide {{ $loop->iteration }}: {{ $slide['title'] }}"
                    ></button>
                @endforeach
            </div>

            <div class="home-hero-progress" aria-hidden="true">
                <span class="home-hero-progress-bar"></span>
            </div>

            <div class="home-hero-counter" aria-live="polite">
                <span data-hero-current>01</span><span>/</span><span>{{ str_pad((string) $heroSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="home-hero-controls">
                <button class="home-hero-control" type="button" data-bs-target="#aconsHeroSlider" data-bs-slide="prev" aria-label="Xem slide trước">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button class="home-hero-control" type="button" data-bs-target="#aconsHeroSlider" data-bs-slide="next" aria-label="Xem slide tiếp theo">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="home-achievements" aria-label="Năng lực ACONS">
    <div class="container-fluid px-0">
        <div class="achievement-grid">
            <div class="achievement-item" data-reveal="fade-up"><strong>{{ $homeContent('home_stat_1_value', ($siteSettings['experience_years'] ?? '10').'+') }}</strong><span>{{ $homeContent('home_stat_1_label', 'Năm kinh nghiệm') }}</span><small>{{ $homeContent('home_stat_1_note', 'Kiến tạo giá trị bền vững') }}</small></div>
            <div class="achievement-item" data-reveal="fade-up" style="--reveal-delay: 80ms"><strong>{{ $homeContent('home_stat_2_value', ($siteSettings['team_count'] ?? '35').'+') }}</strong><span>{{ $homeContent('home_stat_2_label', 'Nhân sự chuyên môn') }}</span><small>{{ $homeContent('home_stat_2_note', 'Kiến trúc · Nội thất · Kỹ thuật') }}</small></div>
            <div class="achievement-item" data-reveal="fade-up" style="--reveal-delay: 160ms"><strong>{{ $homeContent('home_stat_3_value', '1.2M+') }}</strong><span>{{ $homeContent('home_stat_3_label', 'm² thiết kế') }}</span><small>{{ $homeContent('home_stat_3_note', 'Trên nhiều loại hình công trình') }}</small></div>
            <div class="achievement-item" data-reveal="fade-up" style="--reveal-delay: 240ms"><strong>{{ $homeContent('home_stat_4_value', ($siteSettings['project_count'] ?? '120').'+') }}</strong><span>{{ $homeContent('home_stat_4_label', 'Dự án bàn giao') }}</span><small>{{ $homeContent('home_stat_4_note', 'Nhà ở · Thương mại · Văn phòng') }}</small></div>
            <div class="achievement-item" data-reveal="fade-up" style="--reveal-delay: 320ms"><strong>{{ $homeContent('home_stat_5_value', '100%') }}</strong><span>{{ $homeContent('home_stat_5_label', 'Quy trình kiểm soát') }}</span><small>{{ $homeContent('home_stat_5_note', 'Minh bạch chất lượng và tiến độ') }}</small></div>
        </div>
    </div>
</section>

<section class="home-section home-services" id="dich-vu">
    <div class="container">
        <div class="home-kicker" data-reveal="fade">{{ $homeContent('home_services_kicker', 'Dịch vụ của chúng tôi') }}</div>
        <div class="home-section-heading" data-reveal="fade-up" style="--reveal-delay: 80ms">
            <h2>{{ $homeContent('home_services_title', 'Kiến tạo') }} <span class="text-sky">{{ $homeContent('home_services_accent', 'trọn vẹn') }}</span></h2>
            <p>{{ $homeContent('home_services_description', 'Giải pháp đồng bộ từ ý tưởng, thiết kế đến thi công dành cho chủ đầu tư cá nhân và doanh nghiệp.') }}</p>
        </div>

        <div class="home-service-grid">
            @forelse($services->take(4) as $service)
                <article class="home-service-card" data-reveal="fade-up" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <span class="home-service-icon"><i class="bi {{ $service->icon }}"></i></span>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->summary }}</p>
                    @if(!empty($service->process_steps))
                        <div class="home-service-tags">
                            @foreach(collect($service->process_steps)->take(3) as $step)
                                <span>{{ $step }}</span>
                            @endforeach
                        </div>
                    @endif
                    <a href="{{ route('services.index') }}">Tìm hiểu thêm <i class="bi bi-arrow-right"></i></a>
                </article>
            @empty
                <p class="home-empty-state">Danh sách dịch vụ đang được cập nhật.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="home-section home-projects" id="featured-projects">
    <div class="container">
        <div class="home-kicker" data-reveal="fade">{{ $homeContent('home_projects_kicker', 'Dự án nổi bật') }}</div>
        <div class="home-section-heading home-section-heading-light" data-reveal="fade-up" style="--reveal-delay: 80ms">
            <h2>{{ $homeContent('home_projects_title', 'Công trình') }} <span class="text-sky">{{ $homeContent('home_projects_accent', 'định hình') }}</span><br>{{ $homeContent('home_projects_line_two', 'dấu ấn') }}</h2>
            <a class="btn btn-outline-figma" href="{{ route('projects.index') }}">Xem tất cả dự án <i class="bi bi-arrow-up-right"></i></a>
        </div>

        @if($categories->isNotEmpty())
            <div class="project-filter" data-reveal="fade" style="--reveal-delay: 160ms" aria-label="Lọc dự án theo danh mục">
                <button class="filter-btn active" type="button" data-filter="*">Tất cả</button>
                @foreach($categories as $category)
                    <button class="filter-btn" type="button" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>
        @endif

        <div class="home-project-grid" id="featuredProjectsGrid">
            @forelse($homepageProjects as $project)
                <article class="home-project-card project-filter-item project-layout-{{ $loop->iteration }}" data-category="{{ $project->category->slug }}" data-reveal="fade" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <a href="{{ route('projects.show', $project) }}">
                        <img src="{{ $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg') }}" alt="{{ $project->title }}" @if(!$loop->first) loading="lazy" @endif>
                        <span class="home-project-category">{{ $project->category->name }} · {{ $project->style ?: 'Kiến trúc' }}</span>
                        <span class="home-project-info">
                            <strong>{{ $project->title }}</strong>
                            <small>{{ $project->location }} @if($project->completion_year) · {{ $project->completion_year }} @endif</small>
                        </span>
                    </a>
                </article>
            @empty
                <div class="home-empty-state home-empty-state-light">Hãy thêm dự án và đánh dấu “Nổi bật” trong trang quản trị.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="home-section home-difference" id="gioi-thieu">
    <div class="container position-relative">
        <div class="home-kicker" data-reveal="fade">{{ $homeContent('home_difference_kicker', 'Vì sao chọn ACONS') }}</div>
        <h2 class="home-display-heading text-sky" data-reveal="fade-up" style="--reveal-delay: 80ms">{{ $homeContent('home_difference_title', 'Khác biệt') }}</h2>
        <div class="difference-grid">
            @foreach([
                ['bi-stars', '01', $homeContent('home_difference_1_title', 'Tư duy thiết kế'), $homeContent('home_difference_1_copy', 'Mỗi giải pháp bắt đầu từ việc đọc đúng con người, bối cảnh và mục tiêu đầu tư.')],
                ['bi-building-check', '02', $homeContent('home_difference_2_title', 'Khả năng thi công'), $homeContent('home_difference_2_copy', 'Thiết kế luôn được kiểm chứng về tính khả thi, vật liệu, ngân sách và tiến độ.')],
                ['bi-layers', '03', $homeContent('home_difference_3_title', 'Quy trình đồng bộ'), $homeContent('home_difference_3_copy', 'Kiến trúc, nội thất và kỹ thuật phối hợp xuyên suốt trên một hệ tiêu chuẩn.')],
                ['bi-bullseye', '04', $homeContent('home_difference_4_title', 'Cam kết chất lượng'), $homeContent('home_difference_4_copy', 'Từng chi tiết được kiểm soát minh bạch từ hồ sơ đến nghiệm thu công trình.')],
            ] as [$icon, $number, $title, $copy])
                <article class="difference-item" data-reveal="fade-up" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <div class="difference-number"><i class="bi {{ $icon }}"></i><span>{{ $number }}</span></div>
                    <h3>{{ $title }}</h3>
                    <p>{{ $copy }}</p>
                </article>
            @endforeach
        </div>
        <div class="difference-watermark" aria-hidden="true">ACONS</div>
    </div>
</section>

<section class="home-section home-innovation">
    <div class="container">
        <div class="home-kicker home-kicker-centered" data-reveal="fade">{{ $homeContent('home_innovation_kicker', 'Đổi mới trong từng giải pháp') }}</div>
        <div class="innovation-grid">
            <figure class="innovation-visual" data-reveal="fade">
                <img src="{{ $innovationImage }}" alt="Năng lực thiết kế và triển khai của ACONS" loading="lazy">
                <figcaption>
                    <div><strong>{{ $siteSettings['project_count'] ?? '120' }}+</strong><span>Dự án</span></div>
                    <div><strong>0</strong><span>Sai lệch mục tiêu</span></div>
                    <div><strong>24/7</strong><span>Phối hợp</span></div>
                    <div><strong>100%</strong><span>Kiểm soát</span></div>
                </figcaption>
            </figure>
            <div class="innovation-content" data-reveal="fade-up" style="--reveal-delay: 120ms">
                <h2 class="home-display-heading text-sky">{{ $homeContent('home_innovation_title', 'Đổi mới') }}</h2>
                <p>{{ $homeContent('home_innovation_description', 'ACONS ứng dụng công nghệ và quy trình phối hợp liên ngành để rút ngắn thời gian triển khai, đồng thời kiểm soát chất lượng công trình ngay từ giai đoạn thiết kế.') }}</p>
                <ul class="innovation-list">
                    <li><strong>{{ $homeContent('home_innovation_1_title', 'Mô hình hóa BIM') }}</strong><span>{{ $homeContent('home_innovation_1_copy', 'Phối hợp kiến trúc, kết cấu và kỹ thuật trên một mô hình thống nhất.') }}</span></li>
                    <li><strong>{{ $homeContent('home_innovation_2_title', 'Thiết kế tối ưu') }}</strong><span>{{ $homeContent('home_innovation_2_copy', 'Đánh giá công năng, vật liệu và chi phí trước khi bước vào thi công.') }}</span></li>
                    <li><strong>{{ $homeContent('home_innovation_3_title', 'Quản lý minh bạch') }}</strong><span>{{ $homeContent('home_innovation_3_copy', 'Theo dõi đầu việc, tiến độ và tiêu chuẩn nghiệm thu theo từng giai đoạn.') }}</span></li>
                    <li><strong>{{ $homeContent('home_innovation_4_title', 'Giá trị dài hạn') }}</strong><span>{{ $homeContent('home_innovation_4_copy', 'Ưu tiên giải pháp phù hợp khí hậu, tiết kiệm năng lượng và dễ bảo trì.') }}</span></li>
                </ul>
                <a class="btn btn-acons" href="{{ route('services.index') }}">Khám phá năng lực <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

@if($testimonials->isNotEmpty())
<section class="home-testimonial">
    <div class="container">
        @foreach($testimonials->take(1) as $testimonial)
            <blockquote data-reveal="fade">
                <span class="quote-mark">“</span>
                <p>{{ $testimonial->content }}</p>
                <footer>{{ $testimonial->customer_name }} <span>{{ $testimonial->company }}</span></footer>
            </blockquote>
        @endforeach
    </div>
</section>
@endif

@if($partners->isNotEmpty())
<section class="home-partners">
    <div class="container">
        <div class="home-kicker home-kicker-centered" data-reveal="fade">Được tin chọn bởi khách hàng và đối tác</div>
        <div class="partner-row" data-reveal="fade" style="--reveal-delay: 100ms">
            @foreach($partners as $partner)
                <div class="partner-name">
                    @if($partner->logo)
                        <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy">
                    @else
                        <span>{{ $partner->name }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="home-section home-case-studies" id="tin-tuc">
    <div class="container">
        <div class="home-kicker" data-reveal="fade">{{ $homeContent('home_cases_kicker', 'Nghiên cứu điển hình') }}</div>
        <div class="home-section-heading home-section-heading-light" data-reveal="fade-up" style="--reveal-delay: 80ms">
            <h2>{{ $homeContent('home_cases_title', 'Giải pháp cho') }} <span class="text-sky">{{ $homeContent('home_cases_accent', 'những bài toán khó') }}</span></h2>
            <a class="btn btn-outline-figma" href="{{ route('projects.index') }}">Xem thêm <i class="bi bi-arrow-up-right"></i></a>
        </div>
        <div class="case-study-grid">
            @forelse($projects->take(3) as $project)
                <article class="case-study-card" data-reveal="fade-up" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <a href="{{ route('projects.show', $project) }}">
                        <div class="case-study-image">
                            <img src="{{ $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg') }}" alt="{{ $project->title }}" loading="lazy">
                            <span>{{ $project->category->name }} · {{ $project->style ?: 'Kiến trúc' }}</span>
                        </div>
                        <div class="case-study-body">
                            <h3>{{ $project->title }}</h3>
                            <dl>
                                <div><dt>Bối cảnh</dt><dd>{{ $project->summary ?: 'Tối ưu không gian, công năng và trải nghiệm sử dụng.' }}</dd></div>
                                <div><dt>Giải pháp</dt><dd>{{ $project->design_concept ?: 'Tổ chức hình khối và vật liệu theo ngôn ngữ hiện đại.' }}</dd></div>
                                <div><dt>Kết quả</dt><dd>Công trình hài hòa thẩm mỹ, ngân sách và giá trị sử dụng lâu dài.</dd></div>
                            </dl>
                            <span class="case-study-link">Xem chi tiết <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </article>
            @empty
                <div class="home-empty-state home-empty-state-light">Dự án điển hình đang được cập nhật.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="home-contact-cta">
    <div class="container">
        <div class="contact-orbit">
            <div class="contact-orbit-mark" style="--contact-orbit-logo: url('{{ asset('images/acons-symbol.png') }}')" aria-hidden="true"></div>
            <div class="home-kicker home-kicker-centered" data-reveal="fade">{{ $homeContent('home_cta_kicker', 'Bắt đầu một công trình mới') }}</div>
            <h2 data-reveal="fade-up" style="--reveal-delay: 80ms">{{ $homeContent('home_cta_title', 'Cùng ACONS xây dựng') }}<br><span class="text-sky">{{ $homeContent('home_cta_accent', 'không gian tương lai') }}</span></h2>
            <p data-reveal="fade-up" style="--reveal-delay: 160ms">{{ $homeContent('home_cta_description', 'Chia sẻ nhu cầu của bạn, đội ngũ ACONS sẽ liên hệ để tư vấn định hướng phù hợp cho công trình.') }}</p>
            <div class="contact-orbit-actions" data-reveal="fade-up" style="--reveal-delay: 240ms">
                <a class="btn btn-acons" href="{{ route('contacts.create') }}">{{ $homeContent('home_cta_primary_button', 'Nhận tư vấn') }} <i class="bi bi-arrow-right"></i></a>
                <a class="btn btn-outline-dark-figma" href="{{ route('projects.index') }}">{{ $homeContent('home_cta_secondary_button', 'Hồ sơ năng lực') }}</a>
            </div>
            <div class="contact-orbit-details" data-reveal="fade" style="--reveal-delay: 320ms">
                <div><small>Địa chỉ</small><span>{{ $siteSettings['address'] ?? 'Văn phòng ACONS, Việt Nam' }}</span></div>
                <div><small>Email</small><a href="mailto:{{ $siteSettings['email'] ?? 'hello@acons.vn' }}">{{ $siteSettings['email'] ?? 'hello@acons.vn' }}</a></div>
                <div><small>Hotline</small><a href="tel:{{ $siteSettings['hotline'] ?? '' }}">{{ $siteSettings['hotline'] ?? '0900 000 000' }}</a></div>
            </div>
        </div>
    </div>
</section>
@endsection
