@extends('layouts.app')
@section('title', 'Giới thiệu | ACONS')
@section('meta_description', 'Tìm hiểu câu chuyện, đội ngũ, giá trị và cách ACONS kiến tạo những công trình bền vững.')

@section('content')
<article class="editorial-page about-page">
    <header class="editorial-hero about-hero" style="--editorial-hero-image: url('{{ asset('images/hero-architecture.svg') }}')" data-reveal="fade">
        <div class="container editorial-hero-inner">
            <div class="editorial-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><span>/</span>Giới thiệu</div>
            <div class="editorial-kicker editorial-kicker-light">Kiến trúc · Nội thất · Xây dựng</div>
            <h1>Chúng tôi kiến tạo<br><em>giá trị vượt thời gian</em></h1>
            <p>ACONS là tập thể kiến trúc sư, kỹ sư và nhà quản lý cùng theo đuổi một mục tiêu: biến những yêu cầu phức tạp thành không gian rõ ràng, tinh tế và bền vững.</p>
            <div class="editorial-actions"><a href="#our-story" class="btn btn-acons">Câu chuyện ACONS <i class="bi bi-arrow-down"></i></a><a href="{{ route('contacts.create') }}" class="btn btn-outline-figma">Làm việc cùng chúng tôi</a></div>
            <div class="editorial-hero-stats about-hero-stats">
                <div><strong>20+</strong><span>Năm kinh nghiệm</span></div>
                <div><strong>{{ max($projectCount, 240) }}+</strong><span>Dự án hoàn thành</span></div>
                <div><strong>{{ max($serviceCount, 4) }}</strong><span>Lĩnh vực chuyên môn</span></div>
            </div>
        </div>
    </header>

    <section class="editorial-section about-story" id="our-story" data-reveal="fade-up">
        <div class="container about-story-grid">
            <div><div class="editorial-kicker">Câu chuyện ACONS</div><h2>Bắt đầu từ niềm tin<br>vào thiết kế tử tế</h2></div>
            <div><p class="about-story-lead">Một công trình có giá trị không chỉ đẹp ở ngày bàn giao, mà còn cần phù hợp với con người, khí hậu và cách nó được sử dụng trong nhiều năm sau đó.</p><p>Vì vậy, ACONS kết nối thiết kế và thi công trong một quy trình thống nhất. Mỗi quyết định đều được cân nhắc trên ba phương diện: trải nghiệm không gian, tính khả thi và giá trị dài hạn.</p><a href="#values" class="editorial-text-link">Khám phá giá trị của chúng tôi <i class="bi bi-arrow-down"></i></a></div>
        </div>
        <div class="container about-story-visual"><img src="{{ asset('images/hero-architecture.svg') }}" alt="Không gian kiến trúc do ACONS thiết kế"><span>Không gian có bản sắc<br>được tạo nên từ sự thấu hiểu.</span></div>
    </section>

    <section class="editorial-section editorial-section-soft about-values" id="values" data-reveal="fade-up">
        <div class="container">
            <div class="editorial-heading-split"><div><div class="editorial-kicker">Giá trị cốt lõi</div><h2>Nguyên tắc dẫn lối<br>mọi quyết định</h2></div><p>Những giá trị này định hình cách ACONS lắng nghe, thiết kế, phối hợp và chịu trách nhiệm với từng công trình.</p></div>
            <div class="editorial-card-grid">
                @foreach([
                    ['bi-lightbulb', 'Tư duy', 'Đặt câu hỏi đúng trước khi tìm kiếm giải pháp.'],
                    ['bi-eye', 'Minh bạch', 'Thông tin rõ ràng về phạm vi, tiến độ và thay đổi.'],
                    ['bi-diagram-3', 'Đồng hành', 'Một đội ngũ xuyên suốt từ ý tưởng đến bàn giao.'],
                    ['bi-gem', 'Chất lượng', 'Kiểm soát chi tiết với tiêu chuẩn nhất quán.'],
                ] as [$icon, $title, $description])
                    <article class="editorial-card"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><i class="bi {{ $icon }}"></i><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="editorial-section about-team" id="team" data-reveal="fade-up">
        <div class="container about-team-grid">
            <div class="about-team-copy"><div class="editorial-kicker">Đội ngũ liên ngành</div><h2>Một góc nhìn chung,<br>nhiều chuyên môn</h2><p>Kiến trúc sư, kỹ sư kết cấu, kỹ sư MEP và đội ngũ triển khai cùng tham gia từ sớm. Sự phối hợp này giúp ý tưởng được bảo toàn khi đi vào thực tế.</p><div class="about-team-stat"><strong>100+</strong><span>Kiến trúc sư, kỹ sư<br>và cộng sự chuyên môn</span></div></div>
            <div class="about-team-board">
                <div><i class="bi bi-bezier2"></i><strong>Kiến trúc</strong><span>Ý tưởng & không gian</span></div>
                <div><i class="bi bi-grid-3x3"></i><strong>Nội thất</strong><span>Trải nghiệm & vật liệu</span></div>
                <div><i class="bi bi-rulers"></i><strong>Kỹ thuật</strong><span>Kết cấu & hệ thống</span></div>
                <div><i class="bi bi-buildings"></i><strong>Thi công</strong><span>Chất lượng & tiến độ</span></div>
            </div>
        </div>
    </section>

    <section class="about-journey" id="journey" data-reveal="fade">
        <div class="container">
            <div class="editorial-kicker editorial-kicker-light">Hành trình phát triển</div><h2>Từng bước xây dựng<br>một hệ sinh thái đồng bộ</h2>
            <div class="about-timeline">
                <article><span>2006</span><h3>Khởi đầu</h3><p>ACONS hình thành từ nhóm chuyên môn kiến trúc và kỹ thuật.</p></article>
                <article><span>2012</span><h3>Mở rộng năng lực</h3><p>Tích hợp thiết kế nội thất và quản lý triển khai công trình.</p></article>
                <article><span>2018</span><h3>Quy trình đồng bộ</h3><p>Chuẩn hóa phối hợp thiết kế, dự toán và kiểm soát thi công.</p></article>
                <article><span>2026</span><h3>Epsilon</h3><p>Đưa BIM, dữ liệu và công nghệ vào toàn bộ vòng đời dự án.</p></article>
            </div>
        </div>
    </section>

    @if($testimonials->isNotEmpty())
        <section class="editorial-section about-voices" data-reveal="fade-up">
            <div class="container"><div class="editorial-kicker">Khách hàng nói về ACONS</div><div class="about-voice-grid">@foreach($testimonials as $testimonial)<blockquote><i class="bi bi-quote"></i><p>{{ $testimonial->content }}</p><footer><strong>{{ $testimonial->customer_name }}</strong><span>{{ $testimonial->position }}{{ $testimonial->company ? ' · '.$testimonial->company : '' }}</span></footer></blockquote>@endforeach</div></div>
        </section>
    @endif

    <section class="editorial-section editorial-section-soft about-partners" id="partners" data-reveal="fade-up">
        <div class="container"><div class="editorial-heading-split"><div><div class="editorial-kicker">Hệ sinh thái</div><h2>Những đối tác<br>cùng tạo nên giá trị</h2></div><p>ACONS xây dựng mạng lưới đối tác vật liệu, kỹ thuật và công nghệ để mỗi giải pháp đều có khả năng triển khai thực tế.</p></div><div class="about-partner-grid">@forelse($partners as $partner)<div>@if($partner->logo)<img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy">@else<span>{{ $partner->name }}</span>@endif</div>@empty<div>Đối tác vật liệu</div><div>Đối tác kỹ thuật</div><div>Đối tác nội thất</div><div>Đối tác công nghệ</div>@endforelse</div></div>
    </section>

    <section class="editorial-final-cta" data-reveal="fade-up">
        <div class="container"><div class="editorial-kicker">Bắt đầu cùng ACONS</div><h2>Cùng tạo nên một công trình<br>có giá trị lâu dài</h2><p>Chia sẻ với chúng tôi về ý tưởng, khu đất và mục tiêu của bạn.</p><a href="{{ route('contacts.create') }}" class="btn btn-acons">Kết nối với ACONS <i class="bi bi-arrow-right"></i></a></div>
    </section>
</article>
@endsection
