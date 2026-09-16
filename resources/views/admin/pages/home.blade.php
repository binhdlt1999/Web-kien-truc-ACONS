@extends('layouts.admin')

@section('page_title', 'Nội dung Trang chủ')
@section('page_header', 'Chỉnh sửa nội dung Trang chủ')

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Trung tâm nội dung</a>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-eye me-1"></i> Xem Trang chủ</a>
    </div>
@endsection

@section('page_content')
    <form action="{{ route('admin.pages.home.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4 align-items-start">
            <div class="col-xl-9">
                @foreach($sections as $section)
                    <section class="card card-outline card-primary mb-4" id="section-{{ $section['key'] }}">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title fw-semibold mb-1">{{ $section['title'] }}</h2>
                                <div class="small text-secondary">{{ $section['description'] }}</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($section['fields'] as $field)
                                    <div class="{{ $field['type'] === 'textarea' ? 'col-12' : 'col-lg-6' }}">
                                        <label for="{{ $field['key'] }}" class="form-label fw-semibold">{{ $field['label'] }}</label>
                                        @if($field['type'] === 'textarea')
                                            <textarea
                                                id="{{ $field['key'] }}"
                                                name="content[{{ $field['key'] }}]"
                                                rows="3"
                                                maxlength="{{ $field['max'] }}"
                                                class="form-control @error('content.'.$field['key']) is-invalid @enderror"
                                            >{{ old('content.'.$field['key'], $content[$field['key']] ?? $field['fallback']) }}</textarea>
                                        @else
                                            <input
                                                id="{{ $field['key'] }}"
                                                name="content[{{ $field['key'] }}]"
                                                type="text"
                                                maxlength="{{ $field['max'] }}"
                                                value="{{ old('content.'.$field['key'], $content[$field['key']] ?? $field['fallback']) }}"
                                                class="form-control @error('content.'.$field['key']) is-invalid @enderror"
                                            >
                                        @endif
                                        @error('content.'.$field['key'])
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="col-xl-3">
                <div class="card admin-sticky-card">
                    <div class="card-header"><h2 class="card-title fw-semibold">Các vùng nội dung</h2></div>
                    <div class="list-group list-group-flush">
                        @foreach($sections as $section)
                            <a href="#section-{{ $section['key'] }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $section['title'] }}</span>
                                <span class="badge text-bg-light border">{{ count($section['fields']) }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-body border-top">
                        <p class="small text-secondary"><i class="bi bi-shield-check me-1"></i> Nội dung được kiểm tra độ dài và hiển thị an toàn, không cho chèn mã HTML.</p>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy me-1"></i> Lưu toàn bộ thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
