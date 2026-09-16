@extends('layouts.admin')

@section('page_title', 'Đánh giá khách hàng')
@section('page_header', 'Đánh giá khách hàng')
@section('page_description', 'Quản lý những phản hồi được hiển thị trên Trang chủ ACONS.')

@section('page_actions')
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Thêm đánh giá</a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between"><h2 class="card-title fw-semibold mb-0">Danh sách đánh giá</h2><span class="badge text-bg-light border">{{ $testimonials->total() }} đánh giá</span></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Khách hàng</th><th>Nội dung</th><th>Đánh giá</th><th>Hiển thị</th><th>Thứ tự</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3">@if($testimonial->photo)<img src="{{ asset('storage/'.$testimonial->photo) }}" alt="" class="admin-avatar">@else<span class="admin-avatar-placeholder"><i class="bi bi-person"></i></span>@endif<div><strong>{{ $testimonial->customer_name }}</strong><div class="small text-secondary">{{ collect([$testimonial->position, $testimonial->company])->filter()->join(' · ') ?: '—' }}</div></div></div></td>
                            <td class="admin-copy-cell">{{ $testimonial->content }}</td>
                            <td><span class="text-warning text-nowrap" aria-label="{{ $testimonial->rating }} sao">@for($i = 0; $i < $testimonial->rating; $i++)<i class="bi bi-star-fill"></i>@endfor</span></td>
                            <td><span class="badge text-bg-{{ $testimonial->is_active ? 'success' : 'secondary' }}">{{ $testimonial->is_active ? 'Đang hiện' : 'Đã ẩn' }}</span></td>
                            <td>{{ $testimonial->sort_order }}</td>
                            <td class="text-end text-nowrap"><a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-primary">Sửa</a><form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa đánh giá này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-5">Chưa có đánh giá khách hàng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($testimonials->hasPages())<div class="card-footer">{{ $testimonials->links() }}</div>@endif
    </div>
@endsection
