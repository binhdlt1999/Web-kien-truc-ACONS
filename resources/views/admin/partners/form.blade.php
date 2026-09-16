@extends('layouts.admin')

@section('page_title', $partner->exists ? 'Sửa đối tác' : 'Thêm đối tác')
@section('page_header', $partner->exists ? 'Sửa đối tác' : 'Thêm đối tác mới')

@section('page_actions')
    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Danh sách đối tác</a>
@endsection

@section('page_content')
    <form action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($partner->exists) @method('PUT') @endif
        <div class="row justify-content-center"><div class="col-xl-8"><div class="card"><div class="card-header"><h2 class="card-title fw-semibold mb-0">Thông tin đối tác</h2></div><div class="card-body">
            <div class="row g-3">
                <div class="col-md-8"><label for="name" class="form-label fw-semibold">Tên đối tác <span class="text-danger">*</span></label><input id="name" name="name" required maxlength="255" class="form-control" value="{{ old('name', $partner->name) }}"></div>
                <div class="col-md-4"><label for="sort_order" class="form-label fw-semibold">Thứ tự</label><input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="form-control" value="{{ old('sort_order', $partner->sort_order ?? 0) }}"></div>
                <div class="col-12"><label for="url" class="form-label fw-semibold">Website đối tác</label><input id="url" name="url" type="url" maxlength="1000" class="form-control" value="{{ old('url', $partner->url) }}" placeholder="https://example.com"></div>
                <div class="col-12"><label for="logo" class="form-label fw-semibold">Logo</label>@if($partner->logo)<div class="admin-partner-preview mb-3"><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"></div>@endif<input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control"><div class="form-text">Khuyến nghị PNG/WebP nền trong suốt; tối đa 4MB.</div></div>
                <div class="col-12"><input type="hidden" name="is_active" value="0"><div class="form-check form-switch"><input id="is_active" name="is_active" value="1" type="checkbox" class="form-check-input" @checked(old('is_active', $partner->is_active ?? true))><label for="is_active" class="form-check-label">Hiển thị trên website</label></div></div>
            </div>
        </div><div class="card-footer d-flex justify-content-end"><button class="btn btn-primary" type="submit"><i class="bi bi-floppy me-1"></i> Lưu đối tác</button></div></div></div></div>
    </form>
@endsection
