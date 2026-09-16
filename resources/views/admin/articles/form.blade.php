@extends('layouts.admin')

@section('page_title', $article->exists ? 'Sửa bài viết' : 'Thêm bài viết')
@section('page_header', $article->exists ? 'Sửa bài viết' : 'Thêm bài viết mới')
@section('page_description', 'Nội dung sẽ xuất hiện ở khu vực Tài nguyên và tin tức ACONS.')

@section('page_actions')
    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Danh sách bài viết</a>
@endsection

@section('page_content')
    <form action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($article->exists) @method('PUT') @endif

        <div class="row g-4 align-items-start">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header"><h2 class="card-title fw-semibold mb-0">Nội dung bài viết</h2></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                            <input id="title" name="title" required maxlength="255" class="form-control" value="{{ old('title', $article->title) }}">
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label fw-semibold">Slug</label>
                            <div class="input-group"><span class="input-group-text">/tin-tuc/</span><input id="slug" name="slug" maxlength="255" class="form-control" value="{{ old('slug', $article->slug) }}" placeholder="Tự tạo từ tiêu đề nếu để trống"></div>
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label fw-semibold">Mô tả ngắn</label>
                            <textarea id="excerpt" name="excerpt" rows="3" maxlength="1000" class="form-control" placeholder="Tóm tắt nội dung để hiển thị trên danh sách bài viết">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>
                        <div>
                            <label for="body" class="form-label fw-semibold">Nội dung chi tiết</label>
                            <textarea id="body" name="body" rows="16" maxlength="50000" class="form-control" placeholder="Nhập nội dung bài viết...">{{ old('body', $article->body) }}</textarea>
                            <div class="form-text">Nội dung được hiển thị dưới dạng văn bản an toàn; các xuống dòng sẽ được giữ nguyên.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card admin-sticky-card">
                    <div class="card-header"><h2 class="card-title fw-semibold mb-0">Xuất bản</h2></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="draft" @selected(old('status', $article->status ?: 'draft') === 'draft')>Bản nháp</option>
                                <option value="published" @selected(old('status', $article->status) === 'published')>Xuất bản</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="published_at" class="form-label fw-semibold">Thời điểm xuất bản</label>
                            <input id="published_at" name="published_at" type="datetime-local" class="form-control" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
                            <div class="form-text">Nếu để trống khi chọn “Xuất bản”, hệ thống dùng thời gian hiện tại.</div>
                        </div>
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-semibold">Ảnh đại diện</label>
                            @if($article->cover_image)
                                <img src="{{ asset('storage/'.$article->cover_image) }}" alt="Ảnh hiện tại" class="w-100 rounded border mb-2 admin-cover-preview">
                            @endif
                            <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
                            <div class="form-text">JPG, PNG hoặc WebP; tối đa 8MB.</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy me-1"></i> {{ $article->exists ? 'Lưu bài viết' : 'Tạo bài viết' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
