@extends('layouts.app')
@section('title', $project->title.' | ACONS')
@section('meta_description', $project->summary ?: 'Dự án '.$project->title.' do ACONS thiết kế và thi công.')

@php
    $coverImage = $project->cover_image
        ? asset('storage/'.$project->cover_image)
        : asset('images/hero-architecture.svg');
    $blueprintImage = $project->floorPlans->first()
        ? asset('storage/'.$project->floorPlans->first()->path)
        : asset('images/project-placeholder.svg');
    $solutionImage = $project->galleryImages->first()
        ? asset('storage/'.$project->galleryImages->first()->path)
        : $coverImage;
    $formattedArea = $project->area
        ? rtrim(rtrim($project->area, '0'), '.').' m²'
        : 'Đang cập nhật';
    $completionYear = $project->completion_year ?: 'Đang cập nhật';
    $projectStatus = $project->completion_year && $project->completion_year <= now()->year
        ? 'Đã hoàn thành'
        : 'Đang triển khai';
    $galleryItems = $project->galleryImages->map(fn ($image) => [
        'src' => asset('storage/'.$image->path),
        'alt' => $image->alt_text ?: $project->title,
        'caption' => $image->caption,
    ]);

    if ($galleryItems->isEmpty()) {
        $galleryItems = collect([
            ['src' => $coverImage, 'alt' => $project->title, 'caption' => 'Phối cảnh tổng thể dự án'],
            ['src' => $blueprintImage, 'alt' => 'Hồ sơ '.$project->title, 'caption' => 'Nghiên cứu kiến trúc và mặt bằng'],
        ]);
    }

    $challenges = [
        ['Bài toán công năng', 'Tổ chức các khu vực sử dụng mạch lạc, tối ưu diện tích nhưng vẫn đảm bảo sự riêng tư và khả năng kết nối giữa các thành viên.'],
        ['Điều kiện khu đất', 'Giải pháp được phát triển dựa trên hướng nắng, hướng gió, tầm nhìn và các giới hạn thực tế của khu đất.'],
        ['Ngôn ngữ kiến trúc', 'Duy trì một tinh thần thiết kế nhất quán từ hình khối, tỷ lệ mặt đứng đến lựa chọn vật liệu hoàn thiện.'],
        ['Hiệu quả thi công', 'Hồ sơ kỹ thuật được phối hợp sớm để hạn chế xung đột, kiểm soát chi phí và bảo đảm tiến độ triển khai.'],
    ];
    $solutions = [
        ['Tối ưu tổ chức không gian', 'Công năng được phân lớp rõ ràng, ưu tiên luồng di chuyển ngắn và những khoảng mở kết nối với thiên nhiên.'],
        ['Khai thác ánh sáng tự nhiên', 'Hệ cửa, khoảng đệm và mảng xanh được bố trí để đưa ánh sáng sâu vào công trình và giảm bức xạ trực tiếp.'],
        ['Phối hợp hồ sơ đồng bộ', 'Kiến trúc, kết cấu và kỹ thuật được rà soát trên cùng một nguyên tắc thiết kế trước khi thi công.'],
        ['Kiểm soát vật liệu', 'Bảng vật liệu được lựa chọn theo tiêu chí thẩm mỹ, độ bền, khả năng bảo trì và ngân sách đầu tư.'],
        ['Hỗ trợ trong thi công', 'Đội ngũ ACONS theo sát các mốc nghiệm thu quan trọng để ý tưởng thiết kế được chuyển tải chính xác.'],
    ];
@endphp

@section('content')
<article class="project-detail-page">
    <header class="project-detail-hero" style="--project-hero-image: url('{{ $coverImage }}')" data-reveal="fade">
        <div class="container project-detail-hero-inner">
            <div class="project-detail-breadcrumb">Dự án <span>/</span> {{ $project->category->name }}</div>
            <h1>{{ $project->title }}</h1>
            <p>{{ $project->style ?: 'Kiến trúc đương đại' }} · {{ $project->category->name }}</p>

            <div class="project-hero-meta" aria-label="Thông tin nhanh dự án">
                <div><span>Địa điểm</span><strong>{{ $project->location ?: 'Đang cập nhật' }}</strong></div>
                <div><span>Chủ đầu tư</span><strong>{{ $project->client ?: 'Khách hàng tư nhân' }}</strong></div>
                <div><span>Vai trò</span><strong>Thiết kế &amp; triển khai</strong></div>
                <div><span>Trạng thái</span><strong>{{ $projectStatus }}</strong></div>
            </div>

            <div class="project-hero-actions">
                <a href="#project-overview" class="btn btn-acons">Khám phá dự án <i class="bi bi-arrow-down"></i></a>
                <a href="{{ route('projects.index') }}" class="project-inline-link">Xem tất cả dự án <i class="bi bi-arrow-up-right"></i></a>
            </div>
        </div>
        <a href="#project-overview" class="project-scroll-cue" aria-label="Cuộn đến thông tin dự án"><span>Cuộn xuống</span><i class="bi bi-arrow-down"></i></a>
    </header>

    <section class="project-section project-overview" id="project-overview" data-reveal="fade-up">
        <div class="container">
            <div class="project-section-heading project-section-heading-split">
                <div>
                    <div class="project-kicker">01 — Tổng quan dự án</div>
                    <h2>Thông tin<br>dự án</h2>
                </div>
                <div class="project-heading-note">
                    <strong>{{ $project->title }}</strong>
                    <span>{{ $project->location ?: 'Việt Nam' }}</span>
                    <span>{{ $projectStatus }}</span>
                </div>
            </div>

            <dl class="project-facts">
                <div><dt>Loại hình</dt><dd>{{ $project->category->name }}</dd></div>
                <div><dt>Địa điểm</dt><dd>{{ $project->location ?: 'Đang cập nhật' }}</dd></div>
                <div><dt>Chủ đầu tư</dt><dd>{{ $project->client ?: 'Khách hàng tư nhân' }}</dd></div>
                <div><dt>Năm hoàn thành</dt><dd>{{ $completionYear }}</dd></div>
                <div><dt>Tổng diện tích</dt><dd>{{ $formattedArea }}</dd></div>
                <div><dt>Phong cách</dt><dd>{{ $project->style ?: 'Đương đại' }}</dd></div>
                <div><dt>Quy mô</dt><dd>{{ $formattedArea }}</dd></div>
                <div><dt>Trạng thái</dt><dd>{{ $projectStatus }}</dd></div>
                <div><dt>Phạm vi thiết kế</dt><dd>Kiến trúc · Nội thất</dd></div>
                <div class="project-fact-wide"><dt>Vai trò của ACONS</dt><dd>Tư vấn ý tưởng, thiết kế kiến trúc, phối hợp hồ sơ kỹ thuật và hỗ trợ triển khai thi công.</dd></div>
            </dl>
        </div>
    </section>

    <section class="project-section project-story" data-reveal="fade-up">
        <div class="container">
            <div class="project-kicker">02 — Câu chuyện dự án</div>
            <div class="project-story-intro">
                <h2>Thiết kế cho<br>những yêu cầu<br>phức tạp</h2>
                <div>
                    <p class="project-lead">{{ $project->description ?: $project->summary }}</p>
                    @if($project->design_concept)
                        <p>{{ $project->design_concept }}</p>
                    @endif
                    <a href="#project-challenges" class="project-inline-link project-inline-link-dark">Đọc câu chuyện <i class="bi bi-arrow-down"></i></a>
                </div>
            </div>

            <dl class="project-collaborators">
                <div><dt>Định hướng</dt><dd>{{ $project->style ?: 'Kiến trúc đương đại' }}</dd></div>
                <div><dt>Đơn vị thiết kế</dt><dd>ACONS Architecture &amp; Construction</dd></div>
                <div><dt>Loại công trình</dt><dd>{{ $project->category->name }}</dd></div>
                <div><dt>Giai đoạn</dt><dd>Ý tưởng đến hoàn thiện</dd></div>
            </dl>
        </div>
    </section>

    <section class="project-scale" aria-label="Quy mô dự án" data-reveal="fade">
        <div class="container">
            <div class="project-kicker">03 — Quy mô dự án</div>
            <h2>Những con số</h2>
            <div class="project-scale-grid">
                <div><strong>{{ $formattedArea }}</strong><span>Diện tích thiết kế</span></div>
                <div><strong>04</strong><span>Giai đoạn triển khai</span></div>
                <div><strong>{{ $completionYear }}</strong><span>Năm hoàn thành</span></div>
                <div><strong>01</strong><span>Đầu mối thiết kế</span></div>
                <div><strong>03</strong><span>Bộ môn phối hợp</span></div>
                <div><strong>ACONS</strong><span>Đơn vị thực hiện</span></div>
            </div>
        </div>
    </section>

    <section class="project-section project-challenges" id="project-challenges" data-reveal="fade-up">
        <div class="container">
            <div class="project-challenge-grid">
                <div>
                    <div class="project-kicker">04 — Thách thức thiết kế</div>
                    <h2>Bài toán<br>cần giải quyết</h2>
                    <p>{{ $project->summary ?: 'Mỗi dự án bắt đầu từ việc đọc đúng bối cảnh, nhận diện giới hạn và tìm ra cơ hội để tạo nên một không gian khác biệt.' }}</p>
                    <figure class="project-blueprint-thumb">
                        <img src="{{ $blueprintImage }}" alt="Nghiên cứu kỹ thuật {{ $project->title }}" loading="lazy">
                    </figure>
                </div>

                <div class="accordion project-accordion" id="projectChallengeAccordion">
                    @foreach($challenges as [$title, $content])
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#project-challenge-{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="project-challenge-{{ $loop->iteration }}">
                                    <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $title }}
                                </button>
                            </h3>
                            <div id="project-challenge-{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#projectChallengeAccordion">
                                <div class="accordion-body">{{ $content }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="project-section project-solutions" data-reveal="fade-up">
        <div class="container">
            <div class="project-solution-grid">
                <div>
                    <div class="project-kicker">05 — Hướng tiếp cận</div>
                    <h2>Giải pháp của<br>đội ngũ ACONS</h2>
                    <p>{{ $project->construction_solution ?: 'Từng quyết định thiết kế được kiểm chứng qua công năng, vật liệu và khả năng thi công thực tế.' }}</p>

                    <div class="accordion project-accordion project-solution-accordion" id="projectSolutionAccordion">
                        @foreach($solutions as [$title, $content])
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#project-solution-{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="project-solution-{{ $loop->iteration }}">
                                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $title }}
                                    </button>
                                </h3>
                                <div id="project-solution-{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#projectSolutionAccordion">
                                    <div class="accordion-body">{{ $content }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <figure class="project-solution-image">
                    <img src="{{ $solutionImage }}" alt="Giải pháp thiết kế {{ $project->title }}" loading="lazy">
                    <figcaption><span>Giải pháp nổi bật</span><strong>{{ $project->style ?: 'Thiết kế đồng bộ' }}</strong></figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="project-technical" data-reveal="fade-up">
        <div class="container">
            <div class="project-kicker">06 — Hồ sơ kỹ thuật</div>
            <h2>Trực quan hóa thiết kế</h2>
            <div class="project-technical-card">
                <button class="gallery-item project-technical-visual" type="button" data-gallery-src="{{ $blueprintImage }}" data-gallery-alt="Bản vẽ {{ $project->title }}" data-gallery-caption="Hồ sơ kỹ thuật dự án {{ $project->title }}">
                    <img src="{{ $blueprintImage }}" alt="Bản vẽ {{ $project->title }}" loading="lazy">
                    <span>Xem toàn màn hình <i class="bi bi-arrows-fullscreen"></i></span>
                </button>
                <div class="project-technical-notes">
                    <div class="active"><span>01</span><strong>Mặt bằng công năng</strong><small>Tổ chức luồng di chuyển và không gian sử dụng.</small></div>
                    <div><span>02</span><strong>Hình khối kiến trúc</strong><small>Kiểm soát tỷ lệ, ánh sáng và nhịp điệu mặt đứng.</small></div>
                    <div><span>03</span><strong>Chi tiết vật liệu</strong><small>Đồng bộ thẩm mỹ với yêu cầu thi công thực tế.</small></div>
                </div>
            </div>
        </div>
    </section>

    <section class="project-section project-coordination" data-reveal="fade-up">
        <div class="container">
            <div class="project-kicker">07 — Phối hợp thiết kế</div>
            <div class="project-coordination-grid">
                <div>
                    <h2>Thiết kế được<br>kiểm soát đồng bộ</h2>
                    <p>ACONS duy trì một quy trình phối hợp xuyên suốt từ ý tưởng đến hồ sơ thi công, giúp các quyết định được kiểm tra sớm và chuyển giao rõ ràng.</p>
                    <ul>
                        <li><strong>Kiểm tra xung đột</strong><span>Rà soát kiến trúc, kết cấu và hệ thống kỹ thuật.</span></li>
                        <li><strong>Quản lý thay đổi</strong><span>Cập nhật thống nhất giữa bản vẽ, vật liệu và hiện trường.</span></li>
                        <li><strong>Trực quan hóa</strong><span>Mô phỏng không gian để khách hàng dễ dàng ra quyết định.</span></li>
                        <li><strong>Hồ sơ thi công</strong><span>Chi tiết hóa những vị trí quan trọng trước khi triển khai.</span></li>
                    </ul>
                    <div class="project-mini-stats">
                        <div><strong>03</strong><span>Bộ môn</span></div>
                        <div><strong>04</strong><span>Giai đoạn</span></div>
                        <div><strong>01</strong><span>Tiêu chuẩn</span></div>
                    </div>
                </div>
                <figure class="project-compare">
                    <div class="project-compare-half project-compare-design" style="--compare-image: url('{{ $blueprintImage }}')"><span>Thiết kế</span></div>
                    <div class="project-compare-half project-compare-built" style="--compare-image: url('{{ $solutionImage }}')"><span>Hoàn thiện</span></div>
                    <figcaption>Kéo ý tưởng đến gần hiện thực</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="project-section project-gallery-section" data-reveal="fade-up">
        <div class="container">
            <div class="project-section-heading project-section-heading-split">
                <div>
                    <div class="project-kicker">08 — Hình ảnh dự án</div>
                    <h2>Không gian<br>qua từng góc nhìn</h2>
                </div>
                <p>Từ tổng thể kiến trúc đến những chi tiết vật liệu, mỗi góc nhìn đều kể tiếp câu chuyện của {{ $project->title }}.</p>
            </div>

            <div class="project-gallery-grid">
                @foreach($galleryItems as $image)
                    <button class="gallery-item project-gallery-item project-gallery-item-{{ ($loop->index % 5) + 1 }}" type="button" data-gallery-src="{{ $image['src'] }}" data-gallery-alt="{{ $image['alt'] }}" data-gallery-caption="{{ $image['caption'] }}">
                        <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" loading="lazy">
                        <span><i class="bi bi-arrows-fullscreen"></i></span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    @if($project->floorPlans->isNotEmpty())
        <section class="project-section project-floor-plans" data-reveal="fade-up">
            <div class="container">
                <div class="project-kicker">09 — Bản vẽ mặt bằng</div>
                <div class="project-section-heading project-section-heading-split">
                    <h2>Hồ sơ<br>mặt bằng</h2>
                    @if($project->floor_plan_description)<p>{{ $project->floor_plan_description }}</p>@endif
                </div>
                <div class="project-floor-grid">
                    @foreach($project->floorPlans as $image)
                        <button class="gallery-item project-floor-item" type="button" data-gallery-src="{{ asset('storage/'.$image->path) }}" data-gallery-alt="Bản vẽ {{ $project->title }}" data-gallery-caption="{{ $image->caption }}">
                            <img src="{{ asset('storage/'.$image->path) }}" alt="Bản vẽ {{ $project->title }}" loading="lazy">
                            <span>{{ $image->caption ?: 'Mặt bằng '.str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="project-timeline" data-reveal="fade">
        <div class="container">
            <div class="project-kicker">10 — Tiến trình dự án</div>
            <h2>Hành trình kiến tạo</h2>
        </div>
        <div class="project-timeline-track">
            @foreach([
                ['Nghiên cứu', 'Khảo sát hiện trạng và tiếp nhận yêu cầu'],
                ['Ý tưởng', 'Phát triển phương án và ngôn ngữ thiết kế'],
                ['Thiết kế', 'Hoàn thiện công năng, hình khối và vật liệu'],
                ['Kỹ thuật', 'Phối hợp các bộ môn và hồ sơ thi công'],
                ['Triển khai', 'Hỗ trợ giải đáp và kiểm soát tại hiện trường'],
                ['Hoàn thiện', 'Nghiệm thu, bàn giao và đồng hành vận hành'],
            ] as [$title, $content])
                <div class="project-timeline-step">
                    <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $title }}</h3>
                    <p>{{ $content }}</p>
                    <small>ACONS scope</small>
                </div>
            @endforeach
        </div>
    </section>

    <section class="project-section project-outcomes" data-reveal="fade-up">
        <div class="container">
            <div class="project-section-heading project-section-heading-split">
                <div>
                    <div class="project-kicker">11 — Giá trị đạt được</div>
                    <h2>Kết quả dự án</h2>
                </div>
                <p>Một giải pháp cân bằng giữa trải nghiệm sử dụng, thẩm mỹ kiến trúc, khả năng thi công và giá trị lâu dài.</p>
            </div>
            <div class="project-outcome-grid">
                <div><strong>100%</strong><h3>Quy trình đồng bộ</h3><p>Một đầu mối xuyên suốt giúp thông tin được kiểm soát rõ ràng.</p></div>
                <div><strong>04</strong><h3>Giai đoạn phối hợp</h3><p>Từ nghiên cứu, thiết kế, kỹ thuật đến hỗ trợ triển khai.</p></div>
                <div><strong>{{ $formattedArea }}</strong><h3>Diện tích được tối ưu</h3><p>Cân bằng nhu cầu sử dụng với độ thoáng và tính riêng tư.</p></div>
                <div><strong>{{ number_format($project->view_count) }}+</strong><h3>Lượt quan tâm</h3><p>Số lượt khám phá dự án được ghi nhận trên website ACONS.</p></div>
            </div>
        </div>
    </section>

    <section class="project-section project-scope" data-reveal="fade-up">
        <div class="container">
            <div class="project-kicker">12 — Phạm vi ACONS</div>
            <div class="project-scope-grid">
                <div>
                    <h2>ACONS đồng hành</h2>
                    <p>Đội ngũ phụ trách dự án được tổ chức theo mô hình đa bộ môn, kết nối sáng tạo thiết kế với tính khả thi khi triển khai.</p>
                </div>
                <dl>
                    <div><dt>Phạm vi dự án</dt><dd>Ý tưởng đến hồ sơ triển khai</dd></div>
                    <div><dt>Chuyên môn</dt><dd>Kiến trúc · Nội thất · Kỹ thuật</dd></div>
                    <div><dt>Đơn vị thực hiện</dt><dd>ACONS Architecture &amp; Construction</dd></div>
                </dl>
            </div>

            <div class="project-team">
                <div class="project-kicker">13 — Đội ngũ dự án</div>
                <h2>Những người kiến tạo</h2>
                <div class="project-team-grid">
                    @foreach([
                        ['TK', 'Kiến trúc sư chủ trì', 'Định hướng thiết kế và điều phối dự án'],
                        ['NT', 'Thiết kế nội thất', 'Không gian, vật liệu và trải nghiệm sử dụng'],
                        ['KT', 'Điều phối kỹ thuật', 'Phối hợp hồ sơ và xử lý chi tiết'],
                        ['TC', 'Hỗ trợ thi công', 'Kiểm soát thiết kế tại hiện trường'],
                    ] as [$initials, $role, $description])
                        <div><span>{{ $initials }}</span><strong>{{ $role }}</strong><small>{{ $description }}</small></div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($relatedProjects->isNotEmpty())
        <section class="project-section project-related" data-reveal="fade-up">
            <div class="container">
                <div class="project-section-heading project-section-heading-split">
                    <div>
                        <div class="project-kicker">14 — Khám phá thêm</div>
                        <h2>Dự án liên quan</h2>
                    </div>
                    <a href="{{ route('projects.index') }}" class="project-inline-link project-inline-link-dark">Xem tất cả dự án <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="project-related-grid">
                    @foreach($relatedProjects as $related)
                        @php($relatedCover = $related->cover_image ? asset('storage/'.$related->cover_image) : asset('images/project-placeholder.svg'))
                        <article>
                            <a href="{{ route('projects.show', $related) }}">
                                <img src="{{ $relatedCover }}" alt="{{ $related->title }}" loading="lazy">
                                <div><span>{{ $related->category->name }} · {{ $related->location }}</span><h3>{{ $related->title }}</h3></div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="project-contact-cta" data-reveal="fade-up">
        <div class="container">
            <div class="project-kicker">Đồng hành cùng ACONS</div>
            <h2>Bạn có một dự án<br>đang ấp ủ?</h2>
            <p>Hãy chia sẻ với đội ngũ ACONS về nhu cầu của bạn. Chúng tôi sẵn sàng cùng bạn tìm ra một giải pháp kiến trúc phù hợp và bền vững.</p>
            <div>
                <a href="{{ route('contacts.create') }}" class="btn btn-acons">Gửi yêu cầu tư vấn <i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-dark-figma">Xem thêm dự án</a>
            </div>
        </div>
    </section>
</article>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <div id="galleryModalCaption" class="text-white"></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="galleryModalImage" class="mw-100 mh-100 object-fit-contain" src="" alt="">
            </div>
        </div>
    </div>
</div>
@endsection
