@extends('layouts.admin')

@section('page_title', 'Đối tác')
@section('page_header', 'Đối tác ACONS')
@section('page_description', 'Quản lý logo và liên kết đối tác xuất hiện trên website.')

@section('page_actions')
    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Thêm đối tác</a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between"><h2 class="card-title fw-semibold mb-0">Danh sách đối tác</h2><span class="badge text-bg-light border">{{ $partners->total() }} đối tác</span></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Đối tác</th><th>Website</th><th>Hiển thị</th><th>Thứ tự</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3">@if($partner->logo)<span class="admin-logo-shell"><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"></span>@else<span class="admin-logo-shell text-secondary"><i class="bi bi-image"></i></span>@endif<strong>{{ $partner->name }}</strong></div></td>
                            <td>@if($partner->url)<a href="{{ $partner->url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">{{ \Illuminate\Support\Str::limit($partner->url, 42) }} <i class="bi bi-box-arrow-up-right small"></i></a>@else<span class="text-secondary">—</span>@endif</td>
                            <td><span class="badge text-bg-{{ $partner->is_active ? 'success' : 'secondary' }}">{{ $partner->is_active ? 'Đang hiện' : 'Đã ẩn' }}</span></td>
                            <td>{{ $partner->sort_order }}</td>
                            <td class="text-end text-nowrap"><a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-primary">Sửa</a><form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa đối tác này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">Chưa có đối tác.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($partners->hasPages())<div class="card-footer">{{ $partners->links() }}</div>@endif
    </div>
@endsection
