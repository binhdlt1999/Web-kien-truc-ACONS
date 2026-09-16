@extends('layouts.admin')

@section('page_title', $testimonial->exists ? 'Sửa đánh giá' : 'Thêm đánh giá')
@section('page_header', $testimonial->exists ? 'Sửa đánh giá khách hàng' : 'Thêm đánh giá khách hàng')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Danh sách đánh giá</a>
@endsection

@section('page_content')
    <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($testimonial->exists) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card"><div class="card-header"><h2 class="card-title fw-semibold mb-0">Thông tin phản hồi</h2></div><div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label for="customer_name" class="form-label fw-semibold">Tên khách hàng <span class="text-danger">*</span></label><input id="customer_name" name="customer_name" required maxlength="255" class="form-control" value="{{ old('customer_name', $testimonial->customer_name) }}"></div>
                        <div class="col-md-6"><label for="position" class="form-label fw-semibold">Chức vụ/Vai trò</label><input id="position" name="position" maxlength="255" class="form-control" value="{{ old('position', $testimonial->position) }}"></div>
                        <div class="col-md-6"><label for="company" class="form-label fw-semibold">Công ty/Dự án</label><input id="company" name="company" maxlength="255" class="form-control" value="{{ old('company', $testimonial->company) }}"></div>
                        <div class="col-md-3"><label for="rating" class="form-label fw-semibold">Số sao</label><select id="rating" name="rating" class="form-select">@for($rating = 5; $rating >= 1; $rating--)<option value="{{ $rating }}" @selected((int) old('rating', $testimonial->rating ?: 5) === $rating)>{{ $rating }} sao</option>@endfor</select></div>
                        <div class="col-md-3"><label for="sort_order" class="form-label fw-semibold">Thứ tự</label><input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="form-control" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}"></div>
                        <div class="col-12"><label for="content" class="form-label fw-semibold">Nội dung đánh giá <span class="text-danger">*</span></label><textarea id="content" name="content" required rows="7" maxlength="3000" class="form-control">{{ old('content', $testimonial->content) }}</textarea></div>
                    </div>
                </div></div>
            </div>
            <div class="col-lg-4">
                <div class="card"><div class="card-header"><h2 class="card-title fw-semibold mb-0">Hiển thị</h2></div><div class="card-body">
                    <div class="mb-4"><label for="photo" class="form-label fw-semibold">Ảnh khách hàng</label>@if($testimonial->photo)<img src="{{ asset('storage/'.$testimonial->photo) }}" alt="Ảnh hiện tại" class="admin-profile-preview mb-3">@endif<input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control"><div class="form-text">JPG, PNG hoặc WebP; tối đa 4MB.</div></div>
                    <input type="hidden" name="is_active" value="0"><div class="form-check form-switch"><input id="is_active" name="is_active" value="1" type="checkbox" class="form-check-input" @checked(old('is_active', $testimonial->is_active ?? true))><label for="is_active" class="form-check-label">Hiển thị trên website</label></div>
                </div><div class="card-footer bg-transparent"><button class="btn btn-primary w-100" type="submit"><i class="bi bi-floppy me-1"></i> Lưu đánh giá</button></div></div>
            </div>
        </div>
    </form>
@endsection
