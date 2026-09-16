@extends('layouts.admin')

@section('page_title', 'Trung tâm nội dung')
@section('page_header', 'Trung tâm nội dung website')

@section('page_actions')
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
        <i class="bi bi-box-arrow-up-right me-1"></i> Xem website
    </a>
@endsection

@section('page_content')
    <div class="alert alert-light border d-flex align-items-start gap-3 mb-4" role="note">
        <i class="bi bi-info-circle text-primary fs-4"></i>
        <div>
            <strong>Quản lý nội dung theo đúng nơi hiển thị</strong>
            <div class="text-secondary small mt-1">Chọn một trang hoặc một nhóm dữ liệu bên dưới. Nội dung sau khi lưu sẽ được dùng trực tiếp ở website public.</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4 col-md-6">
            <div class="card card-primary card-outline h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-primary"><i class="bi bi-house-door"></i></span>
                        <span class="badge text-bg-success">Đã kết nối CMS</span>
                    </div>
                    <h2 class="h5 mt-4">Trang chủ</h2>
                    <p class="text-secondary">Hero slider, chỉ số năng lực, tiêu đề section, điểm khác biệt, năng lực đổi mới và CTA cuối trang.</p>
                    @php($homeProgress = $homeFieldTotal > 0 ? min(100, (int) round(($homeFieldCount / $homeFieldTotal) * 100)) : 0)
                    <div class="d-flex justify-content-between small mb-1"><span>Đã tùy chỉnh</span><strong>{{ $homeFieldCount }}/{{ $homeFieldTotal }} trường</strong></div>
                    <div class="progress" role="progressbar" aria-label="Mức độ tùy chỉnh Trang chủ" aria-valuenow="{{ $homeProgress }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $homeProgress }}%"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <a href="{{ route('admin.pages.home.edit') }}" class="btn btn-primary w-100">Chỉnh sửa Trang chủ <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-dark"><i class="bi bi-buildings"></i></span>
                        <span class="badge text-bg-primary">{{ $projectCount }} dự án</span>
                    </div>
                    <h2 class="h5 mt-4">Dự án</h2>
                    <p class="text-secondary">Quản lý nội dung chi tiết, thông tin kỹ thuật, ảnh đại diện và gallery của từng công trình.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary w-100">Quản lý dự án</a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-danger"><i class="bi bi-grid"></i></span>
                        <span class="badge text-bg-primary">{{ $serviceCount }} dịch vụ</span>
                    </div>
                    <h2 class="h5 mt-4">Dịch vụ</h2>
                    <p class="text-secondary">Quản lý tên, mô tả, quy trình, icon, trạng thái hiển thị và dịch vụ nổi bật.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pages.edit', 'services') }}" class="btn btn-primary">Nội dung trang Dịch vụ</a>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">Quản lý danh sách dịch vụ</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-info"><i class="bi bi-newspaper"></i></span>
                        <span class="badge text-bg-secondary">{{ $articleCount }} bài viết</span>
                    </div>
                    <h2 class="h5 mt-4">Tài nguyên & bài viết</h2>
                    <p class="text-secondary">Biên tập tiêu đề, mô tả, nội dung chi tiết, ảnh đại diện, trạng thái và thời điểm xuất bản.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pages.edit', 'resources') }}" class="btn btn-primary">Nội dung trang Tài nguyên</a>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-primary">Quản lý bài viết</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-success"><i class="bi bi-chat-quote"></i></span>
                        <span class="badge text-bg-light border">{{ $testimonialCount }} đánh giá · {{ $partnerCount }} đối tác</span>
                    </div>
                    <h2 class="h5 mt-4">Đánh giá & đối tác</h2>
                    <p class="text-secondary">Quản lý phản hồi khách hàng, ảnh đại diện, số sao, logo và liên kết của các đối tác ACONS.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 d-grid gap-2">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-primary">Đánh giá khách hàng</a>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">Đối tác</a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-warning"><i class="bi bi-chat-left-text"></i></span>
                        <span class="badge text-bg-secondary">{{ $contactCount }} yêu cầu</span>
                    </div>
                    <h2 class="h5 mt-4">Liên hệ</h2>
                    <p class="text-secondary">Xử lý yêu cầu tư vấn. Địa chỉ, hotline, email, giờ làm việc và bản đồ nằm trong cấu hình website.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 d-grid gap-2">
                    <a href="{{ route('admin.pages.edit', 'contact') }}" class="btn btn-primary">Nội dung trang Liên hệ</a>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-primary">Xem yêu cầu tư vấn</a>
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn-outline-secondary">Sửa thông tin liên hệ</a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100 admin-page-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <span class="admin-page-icon text-bg-secondary"><i class="bi bi-layout-text-window-reverse"></i></span>
                        <span class="badge text-bg-success">Đã kết nối CMS</span>
                    </div>
                    <h2 class="h5 mt-4">Giới thiệu · Epsilon</h2>
                    <p class="text-secondary">Câu chuyện thương hiệu, giá trị cốt lõi, hành trình phát triển và nội dung nền tảng công nghệ.</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pages.edit', 'about') }}" class="btn btn-primary">Nội dung trang Giới thiệu</a>
                        <a href="{{ route('admin.pages.edit', 'epsilon') }}" class="btn btn-outline-primary">Nội dung trang Epsilon</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-header"><h2 class="card-title fw-semibold">Mức độ tùy chỉnh các trang nội dung</h2></div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($managedPages as $pageKey => $page)
                            @php($progress = $page['field_count'] > 0 ? min(100, (int) round(($page['customized_count'] / $page['field_count']) * 100)) : 0)
                            <div class="col-xl col-md-4 col-sm-6">
                                <a href="{{ route('admin.pages.edit', $pageKey) }}" class="text-decoration-none text-body d-block border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between gap-2 mb-2"><strong>{{ $page['title'] }}</strong><span class="small text-secondary">{{ $page['customized_count'] }}/{{ $page['field_count'] }}</span></div>
                                    <div class="progress" role="progressbar" aria-label="Mức độ tùy chỉnh {{ $page['title'] }}" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width: {{ $progress }}%"></div></div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
