@php
    $companyName = $siteSettings['company_name'] ?? 'ACONS';
    $metaTitle = trim($__env->yieldContent('title')) ?: ($siteSettings['default_meta_title'] ?? 'ACONS | Kiến trúc & Xây dựng');
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: ($siteSettings['default_meta_description'] ?? 'ACONS kiến tạo không gian sống hiện đại, bền vững và giàu bản sắc.');
    $isHomepage = request()->routeIs('home');
@endphp
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="{{ $isHomepage ? 'is-homepage' : 'is-inner-page' }}">
    <nav class="navbar navbar-expand-lg fixed-top site-navbar" aria-label="Điều hướng chính" data-site-header>
        <div class="container site-navbar-inner">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}" aria-label="{{ $companyName }} - Trang chủ">
                @if(!empty($siteSettings['logo']))
                    <img src="{{ asset('storage/'.$siteSettings['logo']) }}" alt="{{ $companyName }}" height="42">
                @else
                    ACONS
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav ms-auto align-items-lg-center site-navigation">
                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}" aria-expanded="false" aria-controls="servicesMegaMenu">
                            Dịch vụ <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-services" id="servicesMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-menu-label">Năng lực chuyên môn</div>
                                <div class="mega-service-grid">
                                    @forelse($navServices as $service)
                                        <a href="{{ route('services.index') }}#service-{{ $service->slug }}" class="mega-service-link">
                                            <i class="bi {{ $service->icon ?: 'bi-building' }}"></i>
                                            <span><strong>{{ $service->name }}</strong><small>{{ $service->summary }}</small></span>
                                        </a>
                                    @empty
                                        <a href="{{ route('services.index') }}" class="mega-service-link"><i class="bi bi-buildings"></i><span><strong>Dịch vụ ACONS</strong><small>Khám phá giải pháp kiến trúc và xây dựng.</small></span></a>
                                    @endforelse
                                </div>
                                <a href="{{ route('services.index') }}" class="mega-footer-link">Xem tất cả dịch vụ <i class="bi bi-arrow-right"></i></a>
                            </div>
                            @php($navProjectCover = $navProject?->cover_image ? asset('storage/'.$navProject->cover_image) : asset('images/hero-architecture.svg'))
                            <aside class="mega-feature-card">
                                <div class="mega-feature-image"><img src="{{ $navProjectCover }}" alt="{{ $navProject?->title ?? 'Dự án ACONS' }}"><span>Dự án nổi bật</span></div>
                                <div class="mega-feature-body">
                                    <strong>{{ $navProject?->title ?? 'Không gian do ACONS kiến tạo' }}</strong>
                                    <p>{{ $navProject?->summary ?? 'Một dự án chọn lọc thể hiện cách ACONS kết nối thiết kế với khả năng triển khai.' }}</p>
                                    <a href="{{ $navProject ? route('projects.show', $navProject) : route('projects.index') }}">Xem dự án <i class="bi bi-arrow-up-right"></i></a>
                                </div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}" aria-expanded="false" aria-controls="projectsMegaMenu">
                            Dự án <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-projects" id="projectsMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-menu-label">Danh mục dự án</div>
                                <div class="mega-project-sectors">
                                    @forelse($navCategories as $category)
                                        <a href="{{ route('projects.index', ['category' => $category->slug]) }}"><strong>{{ $category->name }}</strong><span>{{ $category->projects_count }} dự án</span></a>
                                    @empty
                                        <a href="{{ route('projects.index') }}"><strong>Dự án ACONS</strong><span>Khám phá danh mục</span></a>
                                    @endforelse
                                </div>
                                <a href="{{ route('projects.index') }}" class="mega-project-all">Xem tất cả dự án <i class="bi bi-arrow-right"></i></a>
                            </div>
                            <aside class="mega-project-spotlight">
                                <div class="mega-spotlight-image" style="--spotlight-image: url('{{ $navProjectCover }}')"><span>Tiêu điểm</span></div>
                                <div class="mega-spotlight-body">
                                    <strong>{{ $navProject?->title ?? 'Dự án tiêu biểu ACONS' }}</strong>
                                    <div>
                                        <span><b>{{ $navProject?->area ? rtrim(rtrim($navProject->area, '0'), '.') : '—' }}</b>Diện tích m²</span>
                                        <span><b>{{ $navProject?->completion_year ?? now()->year }}</b>Năm hoàn thành</span>
                                        <span><b>{{ $navProject?->location ?? 'Việt Nam' }}</b>Địa điểm</span>
                                        <span><b>{{ $navProject?->style ?? 'Đương đại' }}</b>Phong cách</span>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('epsilon.*') ? 'active' : '' }}" href="{{ route('epsilon.index') }}" aria-expanded="false" aria-controls="epsilonMegaMenu">
                            Epsilon <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-epsilon" id="epsilonMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-epsilon-brand"><i class="bi bi-hexagon-fill"></i><strong>Epsilon</strong><span>Tech division</span></div>
                                <p class="mega-epsilon-intro">Bộ phận công nghệ của ACONS kết nối kinh nghiệm thiết kế với các công cụ số để nâng cao chất lượng triển khai dự án.</p>
                                <a href="{{ route('epsilon.index') }}#bim" class="mega-epsilon-product">
                                    <span><small>Nền tảng</small><strong>BIM</strong><em>Mô hình thông tin công trình</em></span>
                                    <p>Phối hợp mô hình, kiểm tra xung đột và quản lý dữ liệu thiết kế xuyên suốt.</p>
                                </a>
                                <a href="{{ route('epsilon.index') }}#ai-lab" class="mega-epsilon-product">
                                    <span><small>Đổi mới</small><strong>AI Lab</strong><em>Nghiên cứu ứng dụng</em></span>
                                    <p>Công cụ hỗ trợ phân tích, tối ưu phương án và quản lý tri thức dự án.</p>
                                </a>
                            </div>
                            <aside class="mega-epsilon-aside">
                                <div class="epsilon-orbit"><i></i><i></i><i></i></div>
                                <div><strong>Khi thiết kế gặp công nghệ</strong><p>Epsilon giúp biến dữ liệu thành quyết định thiết kế rõ ràng và hiệu quả hơn.</p><a href="{{ route('epsilon.index') }}#digital-workflow">Khám phá Epsilon</a></div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('resources.*', 'articles.*') ? 'active' : '' }}" href="{{ route('resources.index') }}" aria-expanded="false" aria-controls="resourcesMegaMenu">
                            Tài nguyên <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-resources" id="resourcesMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-menu-label">Trung tâm kiến thức</div>
                                <div class="mega-resource-grid">
                                    <a href="{{ route('resources.index') }}#project-files"><strong>Hồ sơ dự án</strong><span>Phân tích chuyên sâu về ý tưởng và kết quả.</span></a>
                                    <a href="{{ route('resources.index') }}#articles"><strong>Bài viết</strong><span>Góc nhìn chuyên môn về kiến trúc và xây dựng.</span></a>
                                    <a href="{{ route('resources.index') }}#news"><strong>Tin tức</strong><span>Cập nhật hoạt động và câu chuyện từ ACONS.</span></a>
                                    <a href="{{ route('resources.index') }}#knowledge"><strong>Kiến thức</strong><span>Nghiên cứu thiết kế, vật liệu và công nghệ.</span></a>
                                </div>
                                <a href="{{ route('resources.index') }}#guides" class="mega-report-link"><i class="bi bi-graph-up-arrow"></i><span><strong>Cẩm nang kiến tạo không gian bền vững</strong><small>Tài liệu chuyên môn từ đội ngũ ACONS</small></span><em>Đọc ngay <i class="bi bi-arrow-up-right"></i></em></a>
                            </div>
                            @php($navArticleCover = $navArticle?->cover_image ? asset('storage/'.$navArticle->cover_image) : asset('images/project-placeholder.svg'))
                            <aside class="mega-article-card">
                                <div class="mega-article-image"><img src="{{ $navArticleCover }}" alt="{{ $navArticle?->title ?? 'Bài viết ACONS' }}"><span>Góc nhìn</span></div>
                                <div class="mega-article-body"><small>{{ $navArticle?->published_at?->format('d/m/Y') ?? 'Mới nhất' }}</small><strong>{{ $navArticle?->title ?? 'Kiến thức kiến trúc và xây dựng' }}</strong><p>{{ $navArticle?->excerpt ?? 'Khám phá những chia sẻ chuyên môn mới nhất từ ACONS.' }}</p><a href="{{ $navArticle ? route('articles.show', $navArticle) : route('resources.index').'#articles' }}">Đọc bài viết <i class="bi bi-arrow-up-right"></i></a></div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('about.*') ? 'active' : '' }}" href="{{ route('about.index') }}" aria-expanded="false" aria-controls="aboutMegaMenu">
                            Giới thiệu <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-contact mega-menu-about" id="aboutMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-menu-label">Khám phá ACONS</div>
                                <div class="mega-contact-links">
                                    <a href="{{ route('about.index') }}#our-story"><i class="bi bi-book"></i><span><strong>Câu chuyện ACONS</strong><small>Hành trình và triết lý kiến tạo không gian.</small></span><i class="bi bi-arrow-right"></i></a>
                                    <a href="{{ route('about.index') }}#team"><i class="bi bi-people"></i><span><strong>Đội ngũ liên ngành</strong><small>Kiến trúc, nội thất, kỹ thuật và thi công.</small></span><i class="bi bi-arrow-right"></i></a>
                                    <a href="{{ route('about.index') }}#values"><i class="bi bi-gem"></i><span><strong>Giá trị cốt lõi</strong><small>Những nguyên tắc dẫn lối mọi quyết định.</small></span><i class="bi bi-arrow-right"></i></a>
                                </div>
                                <a href="{{ route('about.index') }}#journey" class="mega-about-stat"><strong>20+</strong><span>Năm phát triển<br>cùng ngành xây dựng</span><em>Hành trình ACONS <i class="bi bi-arrow-right"></i></em></a>
                            </div>
                            <aside class="mega-contact-aside">
                                <div class="mega-contact-visual" style="--contact-menu-image: url('{{ asset('images/hero-architecture.svg') }}')"><span>Về chúng tôi</span></div>
                                <div><p>Một tập thể cùng theo đuổi thiết kế tử tế, khả thi và có giá trị dài hạn.</p><a href="{{ route('about.index') }}">Tìm hiểu ACONS</a></div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-mega-item">
                        <a class="nav-link nav-mega-trigger {{ request()->routeIs('contacts.*') ? 'active' : '' }}" href="{{ route('contacts.create') }}" aria-expanded="false" aria-controls="contactMegaMenu">
                            Liên hệ <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu mega-menu-contact" id="contactMegaMenu">
                            <div class="mega-menu-main">
                                <div class="mega-menu-label">Kết nối với ACONS</div>
                                <div class="mega-contact-links">
                                    <a href="{{ route('contacts.create') }}#contact-form"><i class="bi bi-chat-square-text"></i><span><strong>Tư vấn dự án</strong><small>Chia sẻ nhu cầu thiết kế và thi công của bạn.</small></span><i class="bi bi-arrow-right"></i></a>
                                    <a href="mailto:{{ $siteSettings['email'] ?? 'hello@acons.vn' }}"><i class="bi bi-briefcase"></i><span><strong>Hợp tác cùng ACONS</strong><small>Kết nối về chuyên môn, vật liệu và cơ hội hợp tác.</small></span><i class="bi bi-arrow-right"></i></a>
                                    <a href="{{ route('contacts.create') }}#contact-location"><i class="bi bi-geo-alt"></i><span><strong>Ghé thăm văn phòng</strong><small>{{ $siteSettings['address'] ?? 'Văn phòng ACONS, Việt Nam' }}</small></span><i class="bi bi-arrow-right"></i></a>
                                </div>
                                <div class="mega-contact-summary"><span>Hotline</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['hotline'] ?? '') }}">{{ $siteSettings['hotline'] ?? '0900 000 000' }}</a><span>Email</span><a href="mailto:{{ $siteSettings['email'] ?? 'hello@acons.vn' }}">{{ $siteSettings['email'] ?? 'hello@acons.vn' }}</a></div>
                            </div>
                            <aside class="mega-contact-aside">
                                <div class="mega-contact-visual" style="--contact-menu-image: url('{{ asset('images/hero-architecture.svg') }}')"><span>Đội ngũ ACONS</span></div>
                                <div><p>Chúng tôi sẵn sàng lắng nghe và cùng bạn xác định bước đi phù hợp cho dự án.</p><a href="{{ route('contacts.create') }}">Gửi yêu cầu tư vấn</a></div>
                            </aside>
                        </div>
                    </li>

                    <li class="nav-item nav-cta-item"><a class="btn btn-sm btn-acons nav-cta" href="{{ route('contacts.create') }}">Nhận tư vấn <i class="bi bi-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container position-fixed start-50 translate-middle-x" style="top: 88px; z-index: 1100">
            <div class="alert alert-success alert-dismissible shadow" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="footer-logo" aria-label="{{ $companyName }} - Trang chủ">
                        @if(!empty($siteSettings['logo']))
                            <img src="{{ asset('storage/'.$siteSettings['logo']) }}" alt="{{ $companyName }}" height="42">
                        @else
                            ACONS
                        @endif
                    </a>
                    <p>{{ $siteSettings['tagline'] ?? 'Kiến tạo không gian sống hiện đại, bền vững và giàu bản sắc.' }}</p>
                    <small>Kiến trúc · Nội thất · Xây dựng</small>
                </div>
                <div>
                    <h2>Dịch vụ</h2>
                    <ul>
                        <li><a href="{{ route('services.index') }}">Thiết kế kiến trúc</a></li>
                        <li><a href="{{ route('services.index') }}">Thiết kế nội thất</a></li>
                        <li><a href="{{ route('services.index') }}">Xây dựng trọn gói</a></li>
                        <li><a href="{{ route('services.index') }}">Quy hoạch</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Dự án</h2>
                    <ul>
                        <li><a href="{{ route('projects.index') }}">Biệt thự</a></li>
                        <li><a href="{{ route('projects.index') }}">Nhà phố</a></li>
                        <li><a href="{{ route('projects.index') }}">Thương mại</a></li>
                        <li><a href="{{ route('projects.index') }}">Xem tất cả</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Khám phá</h2>
                    <ul>
                        <li><a href="{{ route('home') }}#gioi-thieu">Về ACONS</a></li>
                        <li><a href="{{ route('home') }}#featured-projects">Dự án nổi bật</a></li>
                        <li><a href="{{ route('home') }}#tin-tuc">Câu chuyện</a></li>
                        <li><a href="{{ route('contacts.create') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h2>Liên hệ</h2>
                    <p>{{ $siteSettings['address'] ?? 'Văn phòng ACONS, Việt Nam' }}</p>
                    <a href="tel:{{ $siteSettings['hotline'] ?? '' }}">{{ $siteSettings['hotline'] ?? '0900 000 000' }}</a>
                    <a href="mailto:{{ $siteSettings['email'] ?? 'hello@acons.vn' }}">{{ $siteSettings['email'] ?? 'hello@acons.vn' }}</a>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} {{ $companyName }}. All rights reserved.</span>
                <div class="footer-socials">
                    @if(!empty($siteSettings['facebook_url']))<a href="{{ $siteSettings['facebook_url'] }}" target="_blank" rel="noopener">Facebook</a>@endif
                    @if(!empty($siteSettings['instagram_url']))<a href="{{ $siteSettings['instagram_url'] }}" target="_blank" rel="noopener">Instagram</a>@endif
                    @if(!empty($siteSettings['youtube_url']))<a href="{{ $siteSettings['youtube_url'] }}" target="_blank" rel="noopener">YouTube</a>@endif
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
