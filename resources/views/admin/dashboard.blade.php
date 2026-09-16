@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('page_header', 'Tổng quan hệ thống')
@section('page_description', 'Theo dõi nội dung, dự án và yêu cầu khách hàng trên một màn hình.')

@section('page_actions')
    <a href="{{ route('admin.pages.index') }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i> Chỉnh sửa website</a>
@endsection

@section('page_content')
    <div class="row g-4 mb-4">
        @foreach([
            ['Dự án', $projectCount, 'bi-buildings', 'primary', route('admin.projects.index'), 'Quản lý dự án'],
            ['Đã xuất bản', $publishedCount, 'bi-check-circle', 'success', route('admin.projects.index'), 'Xem nội dung'],
            ['Liên hệ mới', $newContactCount, 'bi-chat-left-text', 'warning', route('admin.contacts.index'), 'Xử lý liên hệ'],
            ['Tổng lượt xem', number_format($totalViews), 'bi-eye', 'info', route('admin.projects.index'), 'Xem thống kê'],
        ] as [$label, $value, $icon, $color, $url, $linkLabel])
            <div class="col-sm-6 col-xl-3">
                <div class="small-box text-bg-{{ $color }} h-100">
                    <div class="inner">
                        <h3>{{ $value }}</h3>
                        <p>{{ $label }}</p>
                    </div>
                    <i class="bi {{ $icon }} small-box-icon"></i>
                    <a href="{{ $url }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        {{ $linkLabel }} <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="card-title fw-semibold mb-0">Yêu cầu tư vấn mới nhất</h2>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Khách hàng</th><th>Dịch vụ</th><th>Trạng thái</th><th class="text-end">Thao tác</th></tr></thead>
                        <tbody>
                            @forelse($latestContacts as $contact)
                                <tr>
                                    <td><strong>{{ $contact->name }}</strong><div class="small text-secondary">{{ $contact->phone }}</div></td>
                                    <td>{{ $contact->service?->name ?? '—' }}</td>
                                    <td><span class="badge text-bg-{{ $contact->status === 'new' ? 'warning' : 'success' }}">{{ $contact->status === 'new' ? 'Chưa xử lý' : 'Đã liên hệ' }}</span></td>
                                    <td class="text-end"><a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">Xem</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-secondary py-5">Chưa có yêu cầu tư vấn.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header"><h2 class="card-title fw-semibold mb-0">Thao tác nhanh</h2></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.pages.home.edit') }}" class="btn btn-primary text-start"><i class="bi bi-house-door me-2"></i> Sửa nội dung Trang chủ</a>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-outline-primary text-start"><i class="bi bi-plus-circle me-2"></i> Thêm dự án mới</a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary text-start"><i class="bi bi-grid me-2"></i> Thêm dịch vụ</a>
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-outline-primary text-start"><i class="bi bi-newspaper me-2"></i> Viết bài mới</a>
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn-outline-secondary text-start"><i class="bi bi-gear me-2"></i> Thông tin công ty & SEO</a>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary text-start"><i class="bi bi-box-arrow-up-right me-2"></i> Mở website public</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header"><h2 class="card-title fw-semibold mb-0">Dự án được xem nhiều</h2></div>
                <ul class="list-group list-group-flush">
                    @forelse($popularProjects as $project)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-3 py-3">
                            <span><strong>{{ $project->title }}</strong><small class="d-block text-secondary">{{ $project->category->name }}</small></span>
                            <span class="badge text-bg-light border"><i class="bi bi-eye me-1"></i>{{ number_format($project->view_count) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-secondary py-4">Chưa có dữ liệu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card card-outline card-primary">
                <div class="card-header"><h2 class="card-title fw-semibold mb-0">Tình trạng nội dung</h2></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Dịch vụ đang hiển thị</span><strong>{{ $serviceCount }}</strong></div>
                    <div class="progress mb-4" style="height: 6px"><div class="progress-bar" style="width: {{ min(100, $serviceCount * 20) }}%"></div></div>
                    <div class="d-flex justify-content-between mb-2"><span>Dự án đã xuất bản</span><strong>{{ $publishedCount }}/{{ $projectCount }}</strong></div>
                    @php($publishProgress = $projectCount > 0 ? (int) round(($publishedCount / $projectCount) * 100) : 0)
                    <div class="progress mb-4" style="height: 6px"><div class="progress-bar bg-success" style="width: {{ $publishProgress }}%"></div></div>
                    <div class="row g-2 mb-4">
                        <div class="col-6"><div class="border rounded p-3 text-center"><strong class="d-block fs-4">{{ $testimonialCount }}</strong><span class="small text-secondary">Đánh giá đang hiện</span></div></div>
                        <div class="col-6"><div class="border rounded p-3 text-center"><strong class="d-block fs-4">{{ $partnerCount }}</strong><span class="small text-secondary">Đối tác đang hiện</span></div></div>
                    </div>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-primary w-100">Mở Trung tâm nội dung</a>
                </div>
            </div>
        </div>
    </div>
@endsection
