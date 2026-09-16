@extends('layouts.app')
@section('title', 'Liên hệ | ACONS')
@section('meta_description', 'Liên hệ ACONS để được tư vấn thiết kế kiến trúc, nội thất và xây dựng.')

@php
    $pageContent = static fn (string $key, string $fallback): string => filled($siteSettings[$key] ?? null) ? (string) $siteSettings[$key] : $fallback;
    $companyName = $siteSettings['company_name'] ?? 'ACONS';
    $address = $siteSettings['address'] ?? 'Văn phòng ACONS, Việt Nam';
    $hotline = $siteSettings['hotline'] ?? '0900 000 000';
    $email = $siteSettings['email'] ?? 'hello@acons.vn';
    $workingHours = $siteSettings['working_hours'] ?? 'Thứ 2 – Thứ 7, 08:00 – 17:30';
    $mapUrl = $siteSettings['google_maps_embed_url'] ?? null;
@endphp

@section('content')
<article class="contact-page">
    <header class="contact-hero" style="--contact-hero-image: url('{{ asset('images/hero-architecture.svg') }}')" data-reveal="fade">
        <div class="container contact-hero-inner">
            <div class="contact-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><span>/</span>Liên hệ</div>
            <div class="contact-kicker contact-kicker-light">{{ $pageContent('contact_hero_kicker', 'Kết nối cùng ACONS') }}</div>
            <h1>{{ $pageContent('contact_hero_title', 'Cùng bắt đầu một công trình mới') }}</h1>
            <p>{{ $pageContent('contact_hero_description', 'Chia sẻ nhu cầu của bạn để đội ngũ ACONS tư vấn định hướng phù hợp cho công trình đang ấp ủ.') }}</p>
            <div class="contact-hero-actions">
                <a href="#contact-form" class="btn btn-acons">{{ $pageContent('contact_hero_primary_button', 'Liên hệ ngay') }} <i class="bi bi-arrow-down"></i></a>
                <a href="#contact-services" class="btn btn-outline-figma">{{ $pageContent('contact_hero_secondary_button', 'Chọn dịch vụ tư vấn') }}</a>
            </div>
            <div class="contact-hero-stats" aria-label="Năng lực ACONS">
                <div><strong>{{ $siteSettings['project_count'] ?? '120' }}+</strong><span>Dự án bàn giao</span></div>
                <div><strong>{{ $siteSettings['experience_years'] ?? '10' }}+</strong><span>Năm kinh nghiệm</span></div>
                <div><strong>{{ $siteSettings['team_count'] ?? '35' }}+</strong><span>Nhân sự chuyên môn</span></div>
                <div><strong>04</strong><span>Dịch vụ cốt lõi</span></div>
            </div>
        </div>
    </header>

    <section class="contact-info-band" aria-label="Thông tin liên hệ ACONS" data-reveal="fade-up">
        <div class="container">
            <div class="contact-info-grid">
                <div>
                    <i class="bi bi-geo-alt"></i>
                    <span>Địa chỉ văn phòng</span>
                    <strong>{{ $companyName }}</strong>
                    <p>{{ $address }}</p>
                </div>
                <div>
                    <i class="bi bi-telephone"></i>
                    <span>Số điện thoại</span>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hotline) }}">{{ $hotline }}</a>
                    <p>Sẵn sàng tiếp nhận yêu cầu tư vấn</p>
                </div>
                <div>
                    <i class="bi bi-envelope"></i>
                    <span>Địa chỉ email</span>
                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                    <p>Phản hồi trong thời gian sớm nhất</p>
                </div>
                <div>
                    <i class="bi bi-clock"></i>
                    <span>Giờ làm việc</span>
                    <strong>{{ $workingHours }}</strong>
                    <p>Chủ nhật: theo lịch hẹn</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-services" id="contact-services" data-reveal="fade-up">
        <div class="container">
            <div class="contact-kicker">{{ $pageContent('contact_services_kicker', 'Dịch vụ') }}</div>
            <h2>{{ $pageContent('contact_services_title', 'Bạn đang quan tâm đến điều gì?') }}</h2>
            <p>{{ $pageContent('contact_services_description', 'Chọn một dịch vụ để chúng tôi chuyển yêu cầu của bạn đến đúng nhóm chuyên môn.') }}</p>
            <div class="contact-service-grid">
                @foreach($services as $service)
                    <button type="button" class="contact-service-option" data-contact-service="{{ $service->id }}" aria-pressed="false">
                        <i class="bi {{ $service->icon ?: 'bi-building' }}"></i>
                        <strong>{{ $service->name }}</strong>
                        <span>{{ $service->summary }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="contact-inquiry" id="contact-form" data-reveal="fade-up">
        <div class="container">
            <div class="contact-inquiry-grid">
                <div class="contact-inquiry-intro">
                    <div class="contact-kicker">{{ $pageContent('contact_form_kicker', 'Gửi yêu cầu tư vấn') }}</div>
                    <h2>{{ $pageContent('contact_form_title', 'Hãy kể chúng tôi về dự án của bạn') }}</h2>
                    <p>{{ $pageContent('contact_form_description', 'Chia sẻ nhu cầu thiết kế, phạm vi công việc hoặc mong muốn của bạn. Đội ngũ ACONS sẽ xem xét kỹ và phản hồi bằng một hướng tiếp cận phù hợp.') }}</p>
                    <ul>
                        <li><i class="bi bi-check2"></i> Phản hồi trong vòng 24 giờ làm việc</li>
                        <li><i class="bi bi-check2"></i> Có chuyên viên phụ trách xuyên suốt</li>
                        <li><i class="bi bi-check2"></i> Bảo mật thông tin dự án</li>
                        <li><i class="bi bi-check2"></i> Buổi trao đổi ban đầu không ràng buộc</li>
                    </ul>
                    <div class="contact-call-direct">
                        <span>Bạn muốn trao đổi trực tiếp?</span>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hotline) }}">{{ $hotline }}</a>
                        <small>{{ $workingHours }}</small>
                    </div>
                </div>
                <div class="contact-form-shell">
                    <x-contact-form :services="$services" />
                </div>
            </div>
        </div>
    </section>

    <section class="contact-location" id="contact-location" data-reveal="fade">
        <div class="contact-location-map">
            @if($mapUrl)
                <iframe src="{{ $mapUrl }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Bản đồ văn phòng ACONS"></iframe>
            @else
                <div class="contact-map-placeholder" aria-label="Sơ đồ vị trí văn phòng ACONS">
                    <span class="contact-map-road contact-map-road-one"></span>
                    <span class="contact-map-road contact-map-road-two"></span>
                    <span class="contact-map-road contact-map-road-three"></span>
                    <i class="bi bi-geo-alt-fill"></i>
                    <strong>Văn phòng ACONS</strong>
                    <small>{{ $address }}</small>
                </div>
            @endif
        </div>
        <div class="contact-location-content">
            <div class="contact-kicker">{{ $pageContent('contact_location_kicker', 'Tìm chúng tôi') }}</div>
            <h2>{{ $pageContent('contact_location_title', 'Văn phòng ACONS') }}</h2>
            <dl>
                <div><dt><i class="bi bi-geo-alt"></i> Địa chỉ</dt><dd>{{ $address }}</dd></div>
                <div><dt><i class="bi bi-clock"></i> Giờ đón khách</dt><dd>{{ $workingHours }}</dd></div>
                <div><dt><i class="bi bi-car-front"></i> Hướng dẫn</dt><dd>Vui lòng liên hệ trước để đội ngũ chuẩn bị buổi tư vấn tốt nhất.</dd></div>
            </dl>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener" class="contact-direction-link">Xem chỉ đường <i class="bi bi-arrow-up-right"></i></a>
        </div>
    </section>

    <section class="contact-why" data-reveal="fade-up">
        <div class="container">
            <div class="contact-why-grid">
                <div>
                    <div class="contact-kicker">{{ $pageContent('contact_why_kicker', 'Vì sao chọn ACONS') }}</div>
                    <h2>{{ $pageContent('contact_why_title', 'Đối tác phù hợp cho một công trình khác biệt') }}</h2>
                </div>
                <div class="contact-benefit-grid">
                    <article><i class="bi bi-people"></i><h3>Đội ngũ đa chuyên môn</h3><p>Kiến trúc, nội thất và kỹ thuật phối hợp trên cùng một định hướng.</p></article>
                    <article><i class="bi bi-lightning-charge"></i><h3>Phản hồi nhanh chóng</h3><p>Mỗi yêu cầu đều có người phụ trách và lộ trình trao đổi rõ ràng.</p></article>
                    <article><i class="bi bi-grid-3x3-gap"></i><h3>Thiết kế đồng bộ</h3><p>Giải pháp được kiểm tra từ công năng, thẩm mỹ đến khả năng thi công.</p></article>
                    <article><i class="bi bi-award"></i><h3>Kinh nghiệm thực tế</h3><p>Kinh nghiệm triển khai nhiều loại hình nhà ở và không gian thương mại.</p></article>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-faq" data-reveal="fade-up">
        <div class="container">
            <div class="contact-faq-grid">
                <div>
                    <div class="contact-kicker">{{ $pageContent('contact_faq_kicker', 'Câu hỏi thường gặp') }}</div>
                    <h2>{{ $pageContent('contact_faq_title', 'Những điều bạn có thể muốn biết') }}</h2>
                    <p>{{ $pageContent('contact_faq_description', 'Nếu chưa tìm thấy câu trả lời phù hợp, hãy gửi email hoặc gọi trực tiếp cho đội ngũ ACONS.') }}</p>
                    <a href="mailto:{{ $email }}">Gửi email cho chúng tôi <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="accordion contact-faq-accordion" id="contactFaqAccordion">
                    @foreach([
                        ['ACONS sẽ phản hồi yêu cầu trong bao lâu?', 'Chúng tôi thường phản hồi trong vòng 24 giờ làm việc. Những yêu cầu cần nghiên cứu chuyên sâu sẽ được xác nhận thời gian xử lý cụ thể.'],
                        ['ACONS nhận thiết kế những loại công trình nào?', 'ACONS tập trung vào nhà ở, biệt thự, nhà phố, nội thất, văn phòng và các không gian thương mại.'],
                        ['Tôi cần chuẩn bị gì cho buổi trao đổi đầu tiên?', 'Bạn có thể chuẩn bị vị trí khu đất, nhu cầu sử dụng, ngân sách dự kiến, thời gian mong muốn và một số hình ảnh tham khảo nếu có.'],
                        ['ACONS có nhận thi công trọn gói không?', 'Có. Tùy vị trí và quy mô dự án, ACONS sẽ đánh giá phạm vi phù hợp và đề xuất kế hoạch triển khai cụ thể.'],
                        ['Chi phí tư vấn và thiết kế được tính như thế nào?', 'Chi phí phụ thuộc loại hình, diện tích, mức độ phức tạp và phạm vi công việc. Báo giá chi tiết được gửi sau khi hai bên thống nhất yêu cầu.'],
                        ['Thông tin dự án của tôi có được bảo mật không?', 'Mọi thông tin được sử dụng cho mục đích tư vấn và triển khai dự án. ACONS có thể ký thỏa thuận bảo mật khi dự án yêu cầu.'],
                    ] as [$question, $answer])
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#contact-faq-{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="contact-faq-{{ $loop->iteration }}">{{ $question }}</button>
                            </h3>
                            <div id="contact-faq-{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#contactFaqAccordion">
                                <div class="accordion-body">{{ $answer }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="contact-final-cta" data-reveal="fade-up">
        <div class="container">
            <h2>{{ $pageContent('contact_cta_title', 'Sẵn sàng bắt đầu dự án tiếp theo?') }}</h2>
            <p>{{ $pageContent('contact_cta_description', 'Đội ngũ ACONS sẵn sàng cùng bạn biến ý tưởng thành một không gian có giá trị lâu dài.') }}</p>
            <div>
                <a href="#contact-form" class="btn contact-cta-light">{{ $pageContent('contact_cta_primary_button', 'Đặt lịch tư vấn') }} <i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('projects.index') }}" class="btn contact-cta-outline">{{ $pageContent('contact_cta_secondary_button', 'Khám phá dự án') }}</a>
            </div>
        </div>
    </section>
</article>
@endsection
