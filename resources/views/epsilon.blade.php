@extends('layouts.app')
@section('title', 'Epsilon — Công nghệ thiết kế | ACONS')
@section('meta_description', 'Epsilon là bộ phận công nghệ của ACONS, ứng dụng BIM, dữ liệu và AI vào thiết kế, phối hợp và quản lý công trình.')

@php
    $pageContent = static fn (string $key, string $fallback): string => filled($siteSettings[$key] ?? null) ? (string) $siteSettings[$key] : $fallback;
    $heroProject = $featuredProjects->first();
    $heroImage = $heroProject?->cover_image
        ? asset('storage/'.$heroProject->cover_image)
        : asset('images/hero-architecture.svg');
@endphp

@section('content')
<article class="editorial-page epsilon-page">
    <header class="editorial-hero epsilon-hero" style="--editorial-hero-image: url('{{ $heroImage }}')" data-reveal="fade">
        <div class="container editorial-hero-inner">
            <div class="editorial-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><span>/</span>Epsilon</div>
            <div class="editorial-kicker editorial-kicker-light"><i class="bi bi-hexagon-fill"></i> {{ $pageContent('epsilon_hero_kicker', 'ACONS Technology Division') }}</div>
            <h1>{{ $pageContent('epsilon_hero_title', 'Công nghệ cho những công trình chính xác hơn') }}</h1>
            <p>{{ $pageContent('epsilon_hero_description', 'Epsilon biến dữ liệu thiết kế thành một ngôn ngữ chung, giúp chủ đầu tư, kiến trúc sư và kỹ sư phối hợp nhanh hơn trong toàn bộ vòng đời dự án.') }}</p>
            <div class="editorial-actions">
                <a href="#epsilon-capabilities" class="btn btn-acons">{{ $pageContent('epsilon_hero_primary_button', 'Khám phá giải pháp') }} <i class="bi bi-arrow-down"></i></a>
                <a href="{{ route('contacts.create') }}" class="btn btn-outline-figma">{{ $pageContent('epsilon_hero_secondary_button', 'Trao đổi cùng chuyên gia') }}</a>
            </div>
            <div class="epsilon-orbit-stage" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
                <strong>ε</strong>
            </div>
            <div class="editorial-hero-stats">
                <div><strong>01</strong><span>Nguồn dữ liệu thống nhất</span></div>
                <div><strong>3D</strong><span>Trực quan hóa không gian</span></div>
                <div><strong>24/7</strong><span>Truy xuất hồ sơ dự án</span></div>
            </div>
        </div>
    </header>

    <section class="editorial-section epsilon-capabilities" id="epsilon-capabilities" data-reveal="fade-up">
        <div class="container">
            <div class="editorial-heading-split">
                <div><div class="editorial-kicker">{{ $pageContent('epsilon_capabilities_kicker', 'Năng lực số') }}</div><h2>{{ $pageContent('epsilon_capabilities_title', 'Một hệ công nghệ cho toàn dự án') }}</h2></div>
                <p>{{ $pageContent('epsilon_capabilities_description', 'Công nghệ không thay thế tư duy thiết kế. Epsilon giúp đội ngũ ACONS kiểm chứng ý tưởng, nhận diện xung đột sớm và kiểm soát từng thay đổi bằng dữ liệu.') }}</p>
            </div>
            <div class="editorial-card-grid editorial-card-grid-three">
                <a href="#bim" class="editorial-card"><span>01</span><i class="bi bi-box"></i><h3>BIM Coordination</h3><p>Phối hợp kiến trúc, kết cấu và MEP trong một mô hình thống nhất.</p><small>Khám phá <i class="bi bi-arrow-down-right"></i></small></a>
                <a href="#ai-lab" class="editorial-card"><span>02</span><i class="bi bi-cpu"></i><h3>AI Lab</h3><p>Hỗ trợ phân tích phương án, dữ liệu và tri thức tích lũy từ dự án.</p><small>Khám phá <i class="bi bi-arrow-down-right"></i></small></a>
                <a href="#digital-workflow" class="editorial-card"><span>03</span><i class="bi bi-diagram-3"></i><h3>Digital Workflow</h3><p>Luồng phê duyệt minh bạch, hồ sơ có phiên bản và dễ dàng truy xuất.</p><small>Khám phá <i class="bi bi-arrow-down-right"></i></small></a>
            </div>
        </div>
    </section>

    <section class="epsilon-platform" id="bim" data-reveal="fade-up">
        <div class="container epsilon-platform-grid">
            <div class="epsilon-blueprint" aria-label="Mô phỏng mô hình BIM">
                <div class="epsilon-blueprint-grid"></div>
                <div class="epsilon-model"><i class="bi bi-buildings"></i><span>BIM MODEL / ACONS</span></div>
                <span class="epsilon-node epsilon-node-one">A</span>
                <span class="epsilon-node epsilon-node-two">B</span>
                <span class="epsilon-node epsilon-node-three">C</span>
            </div>
            <div class="epsilon-platform-content">
                <div class="editorial-kicker editorial-kicker-light">01 / BIM Coordination</div>
                <h2>{{ $pageContent('epsilon_bim_title', 'Thấy vấn đề trước khi nó xuất hiện tại công trường') }}</h2>
                <p>{{ $pageContent('epsilon_bim_description', 'Mô hình BIM cho phép các bộ môn làm việc trên cùng một nguồn thông tin, giảm sai khác giữa thiết kế và thi công.') }}</p>
                <ul class="editorial-check-list">
                    <li><i class="bi bi-check2"></i><span><strong>Phối hợp đa bộ môn</strong>Kiến trúc, kết cấu và kỹ thuật được rà soát đồng thời.</span></li>
                    <li><i class="bi bi-check2"></i><span><strong>Kiểm tra xung đột</strong>Phát hiện sớm điểm giao cắt trước giai đoạn triển khai.</span></li>
                    <li><i class="bi bi-check2"></i><span><strong>Quản lý thay đổi</strong>Mọi cập nhật được kiểm soát theo phiên bản rõ ràng.</span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="editorial-section epsilon-ai" id="ai-lab" data-reveal="fade-up">
        <div class="container epsilon-ai-grid">
            <div>
                <div class="editorial-kicker">02 / AI Lab</div>
                <h2>{{ $pageContent('epsilon_ai_title', 'Tri thức dự án được kích hoạt') }}</h2>
                <p>{{ $pageContent('epsilon_ai_description', 'AI Lab là không gian thử nghiệm các công cụ hỗ trợ đội ngũ đánh giá dữ liệu, so sánh phương án và tìm kiếm thông tin kỹ thuật nhanh hơn.') }}</p>
                <a href="{{ route('contacts.create') }}" class="editorial-text-link">Đề xuất bài toán cùng Epsilon <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="epsilon-ai-cards">
                <article><span>01</span><i class="bi bi-bar-chart-line"></i><h3>Phân tích phương án</h3><p>So sánh công năng, chỉ số và các giới hạn thiết kế theo cùng tiêu chí.</p></article>
                <article><span>02</span><i class="bi bi-search"></i><h3>Tra cứu tri thức</h3><p>Kết nối ghi chú, tiêu chuẩn và bài học đã được kiểm chứng từ dự án.</p></article>
                <article><span>03</span><i class="bi bi-braces"></i><h3>Tự động hóa tác vụ</h3><p>Giảm công việc lặp lại để đội ngũ tập trung vào quyết định quan trọng.</p></article>
                <article><span>04</span><i class="bi bi-shield-check"></i><h3>Kiểm soát con người</h3><p>Mọi đề xuất công nghệ đều được chuyên gia đánh giá trước khi áp dụng.</p></article>
            </div>
        </div>
    </section>

    <section class="editorial-section editorial-section-soft" id="digital-workflow" data-reveal="fade-up">
        <div class="container">
            <div class="editorial-heading-split">
                <div><div class="editorial-kicker">{{ $pageContent('epsilon_workflow_kicker', '03 / Digital workflow') }}</div><h2>{{ $pageContent('epsilon_workflow_title', 'Dòng thông tin không đứt gãy') }}</h2></div>
                <p>{{ $pageContent('epsilon_workflow_description', 'Từ brief ban đầu đến hồ sơ bàn giao, mỗi quyết định đều có ngữ cảnh, người phụ trách và trạng thái rõ ràng.') }}</p>
            </div>
            <div class="editorial-process">
                @foreach([
                    ['Tiếp nhận dữ liệu', 'Tập hợp yêu cầu, hiện trạng và tiêu chuẩn đầu vào.'],
                    ['Xây dựng mô hình', 'Tổ chức thông tin theo cấu trúc thống nhất.'],
                    ['Phối hợp & kiểm tra', 'Rà soát xung đột và ghi nhận quyết định.'],
                    ['Phê duyệt', 'Đồng bộ phiên bản với chủ đầu tư và các bên.'],
                    ['Bàn giao số', 'Lưu trữ hồ sơ dễ truy xuất cho vận hành.'],
                ] as [$title, $description])
                    <article><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
                @endforeach
            </div>
        </div>
    </section>

    @if($featuredProjects->isNotEmpty())
        <section class="editorial-section editorial-projects" data-reveal="fade-up">
            <div class="container">
                <div class="editorial-heading-split"><div><div class="editorial-kicker">{{ $pageContent('epsilon_projects_kicker', 'Ứng dụng thực tế') }}</div><h2>{{ $pageContent('epsilon_projects_title', 'Dự án được hỗ trợ bởi dữ liệu') }}</h2></div><a href="{{ route('projects.index') }}" class="editorial-text-link">Xem tất cả dự án <i class="bi bi-arrow-right"></i></a></div>
                <div class="editorial-project-grid">
                    @foreach($featuredProjects as $project)
                        @php($projectCover = $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg'))
                        <a href="{{ route('projects.show', $project) }}"><img src="{{ $projectCover }}" alt="{{ $project->title }}" loading="lazy"><span>{{ $project->category->name ?? 'Dự án' }} · {{ $project->location }}</span><h3>{{ $project->title }}</h3></a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="editorial-final-cta editorial-final-cta-dark" data-reveal="fade-up">
        <div class="container"><div class="editorial-kicker editorial-kicker-light">{{ $pageContent('epsilon_cta_kicker', 'Kết nối công nghệ và thiết kế') }}</div><h2>{{ $pageContent('epsilon_cta_title', 'Bắt đầu một dự án thông minh hơn') }}</h2><p>{{ $pageContent('epsilon_cta_description', 'Chia sẻ bài toán của bạn để ACONS và Epsilon đề xuất luồng triển khai phù hợp.') }}</p><a href="{{ route('contacts.create') }}" class="btn btn-acons">{{ $pageContent('epsilon_cta_button', 'Trao đổi cùng chúng tôi') }} <i class="bi bi-arrow-right"></i></a></div>
    </section>
</article>
@endsection
