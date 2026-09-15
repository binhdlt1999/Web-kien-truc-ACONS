@extends('layouts.app')

@section('title', 'ACONS | Kiến trúc và Xây dựng')

@php
    $homepageProjects = $projects->take(4)->values();
    $heroProject = $projects->first(fn ($project) => filled($project->cover_image));
    $heroImage = $heroProject?->cover_image
        ? asset('storage/'.$heroProject->cover_image)
        : asset('images/hero-architecture.svg');
    $innovationProject = $projects->first(fn ($project) => filled($project->cover_image) && $project->isNot($heroProject));
    $innovationImage = $innovationProject?->cover_image
        ? asset('storage/'.$innovationProject->cover_image)
        : asset('images/project-placeholder.svg');
@endphp

@section('content')
<section class="home-hero" style="--hero-image: url('{{ $heroImage }}')">
    @if(!empty($siteSettings['hero_video_url']))
        <video class="home-hero-video" autoplay muted loop playsinline poster="{{ $heroImage }}">
            <source src="{{ $siteSettings['hero_video_url'] }}" type="video/mp4">
        </video>
    @endif

    <div class="container home-hero-inner">
        <div class="home-kicker home-kicker-light">Kiến trúc · Nội thất · Xây dựng</div>
        <h1 class="home-hero-title">
            <span>Kiến tạo</span>
            <span class="text-sky">không gian</span>
            <span>vượt thời gian</span>
        </h1>
        <p class="home-hero-copy">{{ $siteSettings['hero_subtitle'] ?? 'ACONS đồng hành từ ý tưởng thiết kế đến thi công hoàn thiện, kiến tạo những công trình giàu bản sắc và bền vững.' }}</p>
        <div class="home-hero-actions">
            <a class="btn btn-acons" href="{{ route('projects.index') }}">Xem dự án <i class="bi bi-arrow-up-right"></i></a>
            <a class="home-text-link home-text-link-light" href="{{ route('contacts.create') }}">Trao đổi cùng ACONS <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="home-hero-stats" aria-label="Thành tựu ACONS">
            <div><strong>{{ $siteSettings['experience_years'] ?? '10' }}+</strong><span>Năm kinh nghiệm</span></div>
            <div><strong>{{ $siteSettings['team_count'] ?? '35' }}+</strong><span>Kiến trúc sư & kỹ sư</span></div>
            <div><strong>{{ $siteSettings['project_count'] ?? '120' }}+</strong><span>Dự án hoàn thành</span></div>
            <div><strong>1.2M+</strong><span>m² đã thiết kế</span></div>
        </div>
    </div>
</section>

<section class="home-achievements" aria-label="Năng lực ACONS">
    <div class="container-fluid px-0">
        <div class="achievement-grid">
            <div class="achievement-item"><strong>{{ $siteSettings['experience_years'] ?? '10' }}+</strong><span>Năm kinh nghiệm</span><small>Kiến tạo giá trị bền vững</small></div>
            <div class="achievement-item"><strong>{{ $siteSettings['team_count'] ?? '35' }}+</strong><span>Nhân sự chuyên môn</span><small>Kiến trúc · Nội thất · Kỹ thuật</small></div>
            <div class="achievement-item"><strong>1.2M+</strong><span>m² thiết kế</span><small>Trên nhiều loại hình công trình</small></div>
            <div class="achievement-item"><strong>{{ $siteSettings['project_count'] ?? '120' }}+</strong><span>Dự án bàn giao</span><small>Nhà ở · Thương mại · Văn phòng</small></div>
            <div class="achievement-item"><strong>100%</strong><span>Quy trình kiểm soát</span><small>Minh bạch chất lượng và tiến độ</small></div>
        </div>
    </div>
</section>

<section class="home-section home-services" id="dich-vu">
    <div class="container">
        <div class="home-kicker">Dịch vụ của chúng tôi</div>
        <div class="home-section-heading">
            <h2>Kiến tạo <span class="text-sky">trọn vẹn</span></h2>
            <p>Giải pháp đồng bộ từ ý tưởng, thiết kế đến thi công dành cho chủ đầu tư cá nhân và doanh nghiệp.</p>
        </div>

        <div class="home-service-grid">
            @forelse($services->take(4) as $service)
                <article class="home-service-card">
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
        <div class="home-kicker">Dự án nổi bật</div>
        <div class="home-section-heading home-section-heading-light">
            <h2>Công trình <span class="text-sky">định hình</span><br>dấu ấn</h2>
            <a class="btn btn-outline-figma" href="{{ route('projects.index') }}">Xem tất cả dự án <i class="bi bi-arrow-up-right"></i></a>
        </div>

        @if($categories->isNotEmpty())
            <div class="project-filter" aria-label="Lọc dự án theo danh mục">
                <button class="filter-btn active" type="button" data-filter="*">Tất cả</button>
                @foreach($categories as $category)
                    <button class="filter-btn" type="button" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>
        @endif

        <div class="home-project-grid" id="featuredProjectsGrid">
            @forelse($homepageProjects as $project)
                <article class="home-project-card project-filter-item project-layout-{{ $loop->iteration }}" data-category="{{ $project->category->slug }}">
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
        <div class="home-kicker">Vì sao chọn ACONS</div>
        <h2 class="home-display-heading text-sky">Khác biệt</h2>
        <div class="difference-grid">
            @foreach([
                ['bi-stars', '01', 'Tư duy thiết kế', 'Mỗi giải pháp bắt đầu từ việc đọc đúng con người, bối cảnh và mục tiêu đầu tư.'],
                ['bi-building-check', '02', 'Khả năng thi công', 'Thiết kế luôn được kiểm chứng về tính khả thi, vật liệu, ngân sách và tiến độ.'],
                ['bi-layers', '03', 'Quy trình đồng bộ', 'Kiến trúc, nội thất và kỹ thuật phối hợp xuyên suốt trên một hệ tiêu chuẩn.'],
                ['bi-bullseye', '04', 'Cam kết chất lượng', 'Từng chi tiết được kiểm soát minh bạch từ hồ sơ đến nghiệm thu công trình.'],
            ] as [$icon, $number, $title, $copy])
                <article class="difference-item">
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
        <div class="home-kicker home-kicker-centered">Đổi mới trong từng giải pháp</div>
        <div class="innovation-grid">
            <figure class="innovation-visual">
                <img src="{{ $innovationImage }}" alt="Năng lực thiết kế và triển khai của ACONS" loading="lazy">
                <figcaption>
                    <div><strong>{{ $siteSettings['project_count'] ?? '120' }}+</strong><span>Dự án</span></div>
                    <div><strong>0</strong><span>Sai lệch mục tiêu</span></div>
                    <div><strong>24/7</strong><span>Phối hợp</span></div>
                    <div><strong>100%</strong><span>Kiểm soát</span></div>
                </figcaption>
            </figure>
            <div class="innovation-content">
                <h2 class="home-display-heading text-sky">Đổi mới</h2>
                <p>ACONS ứng dụng công nghệ và quy trình phối hợp liên ngành để rút ngắn thời gian triển khai, đồng thời kiểm soát chất lượng công trình ngay từ giai đoạn thiết kế.</p>
                <ul class="innovation-list">
                    <li><strong>Mô hình hóa BIM</strong><span>Phối hợp kiến trúc, kết cấu và kỹ thuật trên một mô hình thống nhất.</span></li>
                    <li><strong>Thiết kế tối ưu</strong><span>Đánh giá công năng, vật liệu và chi phí trước khi bước vào thi công.</span></li>
                    <li><strong>Quản lý minh bạch</strong><span>Theo dõi đầu việc, tiến độ và tiêu chuẩn nghiệm thu theo từng giai đoạn.</span></li>
                    <li><strong>Giá trị dài hạn</strong><span>Ưu tiên giải pháp phù hợp khí hậu, tiết kiệm năng lượng và dễ bảo trì.</span></li>
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
            <blockquote>
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
        <div class="home-kicker home-kicker-centered">Được tin chọn bởi khách hàng và đối tác</div>
        <div class="partner-row">
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
        <div class="home-kicker">Nghiên cứu điển hình</div>
        <div class="home-section-heading home-section-heading-light">
            <h2>Giải pháp cho <span class="text-sky">những bài toán khó</span></h2>
            <a class="btn btn-outline-figma" href="{{ route('projects.index') }}">Xem thêm <i class="bi bi-arrow-up-right"></i></a>
        </div>
        <div class="case-study-grid">
            @forelse($projects->take(3) as $project)
                <article class="case-study-card">
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
            <div class="contact-orbit-mark" aria-hidden="true">A</div>
            <div class="home-kicker home-kicker-centered">Bắt đầu một công trình mới</div>
            <h2>Cùng ACONS xây dựng<br><span class="text-sky">không gian tương lai</span></h2>
            <p>Chia sẻ nhu cầu của bạn, đội ngũ ACONS sẽ liên hệ để tư vấn định hướng phù hợp cho công trình.</p>
            <div class="contact-orbit-actions">
                <a class="btn btn-acons" href="{{ route('contacts.create') }}">Nhận tư vấn <i class="bi bi-arrow-right"></i></a>
                <a class="btn btn-outline-dark-figma" href="{{ route('projects.index') }}">Hồ sơ năng lực</a>
            </div>
            <div class="contact-orbit-details">
                <div><small>Địa chỉ</small><span>{{ $siteSettings['address'] ?? 'Văn phòng ACONS, Việt Nam' }}</span></div>
                <div><small>Email</small><a href="mailto:{{ $siteSettings['email'] ?? 'hello@acons.vn' }}">{{ $siteSettings['email'] ?? 'hello@acons.vn' }}</a></div>
                <div><small>Hotline</small><a href="tel:{{ $siteSettings['hotline'] ?? '' }}">{{ $siteSettings['hotline'] ?? '0900 000 000' }}</a></div>
            </div>
        </div>
    </div>
</section>
@endsection
