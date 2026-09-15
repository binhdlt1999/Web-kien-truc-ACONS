@extends('layouts.app')
@section('title', 'Tài nguyên kiến trúc & xây dựng | ACONS')
@section('meta_description', 'Thư viện dự án, bài viết, tin tức và kiến thức chuyên môn về kiến trúc, nội thất và xây dựng từ ACONS.')

@php
    $featuredArticle = $articles->first();
    $featuredArticleImage = $featuredArticle?->cover_image
        ? asset('storage/'.$featuredArticle->cover_image)
        : asset('images/hero-architecture.svg');
@endphp

@section('content')
<article class="editorial-page resources-page">
    <header class="editorial-hero resources-hero">
        <div class="container editorial-hero-inner">
            <div class="editorial-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><span>/</span>Tài nguyên</div>
            <div class="editorial-kicker">Trung tâm kiến thức ACONS</div>
            <h1>Góc nhìn tạo nên<br><em>công trình bền vững</em></h1>
            <p>Khám phá hồ sơ dự án, kinh nghiệm thiết kế, kiến thức thi công và những câu chuyện đang định hình cách ACONS kiến tạo không gian.</p>
            <div class="editorial-actions">
                <a href="#articles" class="btn btn-acons">Bài viết mới nhất <i class="bi bi-arrow-down"></i></a>
                <a href="#project-files" class="btn btn-outline-dark-figma">Xem hồ sơ dự án</a>
            </div>
            <div class="resources-hero-art" aria-hidden="true"><span>R</span><i></i><i></i><i></i></div>
        </div>
    </header>

    <section class="resources-directory" id="knowledge">
        <div class="container">
            <div class="resources-directory-grid">
                <a href="#project-files"><span>01</span><i class="bi bi-buildings"></i><div><h2>Hồ sơ dự án</h2><p>Ý tưởng, giải pháp và kết quả của các công trình ACONS.</p></div><i class="bi bi-arrow-down-right"></i></a>
                <a href="#articles"><span>02</span><i class="bi bi-journal-text"></i><div><h2>Bài viết</h2><p>Góc nhìn chuyên môn từ kiến trúc sư và kỹ sư.</p></div><i class="bi bi-arrow-down-right"></i></a>
                <a href="#news"><span>03</span><i class="bi bi-megaphone"></i><div><h2>Tin tức</h2><p>Hoạt động, sự kiện và câu chuyện mới từ ACONS.</p></div><i class="bi bi-arrow-down-right"></i></a>
                <a href="#guides"><span>04</span><i class="bi bi-compass"></i><div><h2>Kiến thức</h2><p>Cẩm nang thực tế trước khi thiết kế và xây dựng.</p></div><i class="bi bi-arrow-down-right"></i></a>
            </div>
        </div>
    </section>

    <section class="editorial-section resources-feature" id="articles">
        <div class="container">
            <div class="editorial-heading-split"><div><div class="editorial-kicker">Mới nhất</div><h2>Góc nhìn<br>chuyên môn</h2></div><p>Những phân tích ngắn gọn giúp chủ đầu tư hiểu rõ hơn về không gian, vật liệu, chi phí và quy trình triển khai.</p></div>
            @if($featuredArticle)
                <a href="{{ route('articles.show', $featuredArticle) }}" class="resources-featured-article">
                    <img src="{{ $featuredArticleImage }}" alt="{{ $featuredArticle->title }}">
                    <div><span>{{ $featuredArticle->published_at?->format('d.m.Y') }} · Góc nhìn</span><h3>{{ $featuredArticle->title }}</h3><p>{{ $featuredArticle->excerpt }}</p><small>Đọc bài viết <i class="bi bi-arrow-up-right"></i></small></div>
                </a>
            @else
                <div class="resources-featured-article resources-featured-placeholder">
                    <img src="{{ $featuredArticleImage }}" alt="Thư viện kiến thức ACONS">
                    <div><span>Góc nhìn ACONS</span><h3>5 nguyên tắc tạo nên một công trình bền vững</h3><p>Từ bối cảnh, công năng đến vật liệu và vận hành, một công trình tốt bắt đầu bằng những quyết định đúng ngay từ giai đoạn đầu.</p><small>Nội dung đang được cập nhật</small></div>
                </div>
            @endif
            <div class="resources-article-grid">
                @forelse($articles->skip(1) as $article)
                    @php($articleCover = $article->cover_image ? asset('storage/'.$article->cover_image) : asset('images/project-placeholder.svg'))
                    <article><a href="{{ route('articles.show', $article) }}"><img src="{{ $articleCover }}" alt="{{ $article->title }}" loading="lazy"><span>{{ $article->published_at?->format('d.m.Y') }}</span><h3>{{ $article->title }}</h3><p>{{ $article->excerpt }}</p><small>Đọc tiếp <i class="bi bi-arrow-right"></i></small></a></article>
                @empty
                    @foreach([
                        ['Vật liệu tự nhiên trong không gian đương đại', 'Cách lựa chọn vật liệu cân bằng thẩm mỹ, độ bền và khả năng bảo trì.'],
                        ['Chuẩn bị brief thiết kế hiệu quả', 'Những thông tin chủ đầu tư nên xác định trước buổi làm việc đầu tiên.'],
                        ['Ánh sáng và trải nghiệm không gian', 'Tổ chức ánh sáng tự nhiên như một phần của ngôn ngữ kiến trúc.'],
                    ] as [$title, $excerpt])
                        <article class="is-placeholder"><div><span>Kiến thức</span><h3>{{ $title }}</h3><p>{{ $excerpt }}</p><small>Sắp xuất bản</small></div></article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="editorial-section editorial-section-dark resources-project-files" id="project-files">
        <div class="container">
            <div class="editorial-heading-split"><div><div class="editorial-kicker editorial-kicker-light">Hồ sơ dự án</div><h2>Giải pháp phía sau<br>mỗi công trình</h2></div><a href="{{ route('projects.index') }}" class="editorial-text-link editorial-text-link-light">Toàn bộ dự án <i class="bi bi-arrow-right"></i></a></div>
            <div class="editorial-project-grid">
                @forelse($featuredProjects as $project)
                    @php($projectCover = $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg'))
                    <a href="{{ route('projects.show', $project) }}"><img src="{{ $projectCover }}" alt="{{ $project->title }}" loading="lazy"><span>{{ $project->category->name ?? 'Dự án' }} · {{ $project->location }}</span><h3>{{ $project->title }}</h3></a>
                @empty
                    <a href="{{ route('projects.index') }}"><img src="{{ asset('images/hero-architecture.svg') }}" alt="Dự án ACONS"><span>Hồ sơ chọn lọc</span><h3>Khám phá dự án ACONS</h3></a>
                @endforelse
            </div>
        </div>
    </section>

    <section class="editorial-section resources-guides" id="guides">
        <div class="container resources-guides-grid">
            <div><div class="editorial-kicker">Cẩm nang ACONS</div><h2>Kiến thức cần biết<br>trước khi bắt đầu</h2><p>Các nội dung được tổ chức theo từng giai đoạn để bạn dễ tìm đúng thông tin đang cần.</p></div>
            <div class="resources-guide-list">
                <a href="{{ route('services.index') }}"><span>01</span><div><strong>Chọn phạm vi dịch vụ</strong><small>Hiểu sự khác nhau giữa thiết kế, thi công và gói đồng bộ.</small></div><i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('projects.index') }}"><span>02</span><div><strong>Xác định phong cách</strong><small>Tham khảo công trình theo loại hình và ngôn ngữ kiến trúc.</small></div><i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('contacts.create') }}"><span>03</span><div><strong>Chuẩn bị ngân sách</strong><small>Trao đổi mục tiêu và giới hạn để nhận định hướng phù hợp.</small></div><i class="bi bi-arrow-up-right"></i></a>
            </div>
        </div>
    </section>

    <section class="resources-news-strip" id="news">
        <div class="container"><span>Tin tức ACONS</span><p>Theo dõi hoạt động mới, câu chuyện công trường và các chương trình chuyên môn của đội ngũ.</p><a href="{{ route('contacts.create') }}">Kết nối với ACONS <i class="bi bi-arrow-right"></i></a></div>
    </section>
</article>
@endsection
