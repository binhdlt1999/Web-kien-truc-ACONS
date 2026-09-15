@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('page_header', 'Tổng quan hệ thống')

@section('page_content')
<div class="row g-3 mb-4">
    @foreach([
        ['Dự án', $projectCount, 'bi-buildings', 'primary'],
        ['Đã xuất bản', $publishedCount, 'bi-check-circle', 'success'],
        ['Liên hệ mới', $newContactCount, 'bi-chat-left-text', 'warning'],
        ['Tổng lượt xem', number_format($totalViews), 'bi-eye', 'info'],
    ] as [$label,$value,$icon,$color])
        <div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body d-flex justify-content-between"><div><div class="text-secondary">{{ $label }}</div><div class="fs-2 fw-bold">{{ $value }}</div></div><i class="bi {{ $icon }} fs-1 text-{{ $color }}"></i></div></div></div>
    @endforeach
</div>
<div class="row g-4">
    <div class="col-xl-7"><div class="card"><div class="card-header"><h2 class="card-title">Yêu cầu tư vấn mới nhất</h2></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Khách hàng</th><th>Dịch vụ</th><th>Trạng thái</th><th></th></tr></thead><tbody>@forelse($latestContacts as $contact)<tr><td><strong>{{ $contact->name }}</strong><div class="small text-secondary">{{ $contact->phone }}</div></td><td>{{ $contact->service?->name ?? '—' }}</td><td><span class="badge text-bg-{{ $contact->status === 'new' ? 'warning' : 'success' }}">{{ $contact->status === 'new' ? 'Chưa xử lý' : 'Đã liên hệ' }}</span></td><td><a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-secondary">Xem</a></td></tr>@empty<tr><td colspan="4" class="text-center py-4">Chưa có yêu cầu.</td></tr>@endforelse</tbody></table></div></div></div>
    <div class="col-xl-5"><div class="card"><div class="card-header"><h2 class="card-title">Dự án được xem nhiều</h2></div><ul class="list-group list-group-flush">@forelse($popularProjects as $project)<li class="list-group-item d-flex justify-content-between"><span>{{ $project->title }}<small class="d-block text-secondary">{{ $project->category->name }}</small></span><strong>{{ number_format($project->view_count) }}</strong></li>@empty<li class="list-group-item">Chưa có dữ liệu.</li>@endforelse</ul></div></div>
</div>
@endsection
