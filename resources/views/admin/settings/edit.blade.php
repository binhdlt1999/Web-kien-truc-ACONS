@extends('layouts.admin')

@section('page_title', 'Cấu hình website')
@section('page_header', 'Cấu hình website')
@section('page_description', 'Quản lý thông tin dùng chung, nhận diện thương hiệu, SEO và các kênh liên hệ của ACONS.')

@section('page_actions')
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
        <i class="bi bi-box-arrow-up-right me-1"></i> Xem website
    </a>
@endsection

@section('page_content')
    <div class="alert alert-light border d-flex gap-3 align-items-start mb-4" role="note">
        <i class="bi bi-info-circle text-primary fs-4"></i>
        <div>
            <strong>Nội dung Trang chủ đã được tách riêng.</strong>
            <div class="small text-secondary mt-1">
                Tiêu đề Hero, mô tả các slide và chỉ số năng lực được chỉnh tại
                <a href="{{ route('admin.pages.home.edit') }}" class="alert-link">Quản lý trang → Trang chủ</a>.
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4 align-items-start">
            <div class="col-xl-8">
                <section class="card card-outline card-primary mb-4" id="branding-settings">
                    <div class="card-header">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Thương hiệu</h2>
                            <div class="small text-secondary">Tên, thông điệp và logo hiển thị tại header hoặc footer.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="company_name" class="form-label fw-semibold">Tên công ty</label>
                                <input
                                    id="company_name"
                                    name="settings[company_name]"
                                    class="form-control @error('settings.company_name') is-invalid @enderror"
                                    value="{{ old('settings.company_name', $settings['company_name'] ?? '') }}"
                                    maxlength="255"
                                >
                                @error('settings.company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7">
                                <label for="tagline" class="form-label fw-semibold">Thông điệp thương hiệu</label>
                                <input
                                    id="tagline"
                                    name="settings[tagline]"
                                    class="form-control @error('settings.tagline') is-invalid @enderror"
                                    value="{{ old('settings.tagline', $settings['tagline'] ?? '') }}"
                                    maxlength="255"
                                >
                                <div class="form-text">Hiển thị trong phần giới thiệu ngắn ở footer.</div>
                                @error('settings.tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="logo" class="form-label fw-semibold">Logo đầy đủ</label>
                                <div class="row g-3 align-items-center">
                                    @if(filled($settings['logo'] ?? null))
                                        <div class="col-auto">
                                            <div class="border rounded-3 bg-body-tertiary p-3">
                                                <img
                                                    src="{{ asset('storage/'.$settings['logo']) }}"
                                                    alt="Logo ACONS hiện tại"
                                                    style="display:block; max-width:180px; max-height:64px; object-fit:contain"
                                                >
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col">
                                        <input
                                            type="file"
                                            id="logo"
                                            name="logo"
                                            class="form-control @error('logo') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/webp"
                                        >
                                        <div class="form-text">JPG, PNG hoặc WebP; tối đa 4 MB. Để trống nếu không thay logo hiện tại.</div>
                                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-primary mb-4" id="contact-settings">
                    <div class="card-header">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Thông tin liên hệ</h2>
                            <div class="small text-secondary">Dùng tại footer, menu Liên hệ và trang Liên hệ.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Địa chỉ văn phòng</label>
                                <input
                                    id="address"
                                    name="settings[address]"
                                    class="form-control @error('settings.address') is-invalid @enderror"
                                    value="{{ old('settings.address', $settings['address'] ?? '') }}"
                                    maxlength="500"
                                >
                                @error('settings.address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="hotline" class="form-label fw-semibold">Hotline</label>
                                <input
                                    id="hotline"
                                    name="settings[hotline]"
                                    class="form-control @error('settings.hotline') is-invalid @enderror"
                                    value="{{ old('settings.hotline', $settings['hotline'] ?? '') }}"
                                    maxlength="30"
                                >
                                @error('settings.hotline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="settings[email]"
                                    class="form-control @error('settings.email') is-invalid @enderror"
                                    value="{{ old('settings.email', $settings['email'] ?? '') }}"
                                    maxlength="255"
                                >
                                @error('settings.email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="working_hours" class="form-label fw-semibold">Giờ làm việc</label>
                                <input
                                    id="working_hours"
                                    name="settings[working_hours]"
                                    class="form-control @error('settings.working_hours') is-invalid @enderror"
                                    value="{{ old('settings.working_hours', $settings['working_hours'] ?? '') }}"
                                    maxlength="255"
                                >
                                @error('settings.working_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="google_maps_embed_url" class="form-label fw-semibold">Google Maps Embed URL</label>
                                <input
                                    type="url"
                                    id="google_maps_embed_url"
                                    name="settings[google_maps_embed_url]"
                                    class="form-control @error('settings.google_maps_embed_url') is-invalid @enderror"
                                    value="{{ old('settings.google_maps_embed_url', $settings['google_maps_embed_url'] ?? '') }}"
                                    placeholder="https://www.google.com/maps/embed?..."
                                >
                                <div class="form-text">Chỉ dán giá trị trong thuộc tính <code>src</code> của mã nhúng Google Maps.</div>
                                @error('settings.google_maps_embed_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-primary mb-4" id="homepage-media-settings">
                    <div class="card-header">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Media Trang chủ</h2>
                            <div class="small text-secondary">Video tùy chọn chạy trên slide đầu tiên; ảnh dự án vẫn là ảnh dự phòng.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <label for="hero_video_url" class="form-label fw-semibold">URL video MP4 của Hero</label>
                        <input
                            type="url"
                            id="hero_video_url"
                            name="settings[hero_video_url]"
                            class="form-control @error('settings.hero_video_url') is-invalid @enderror"
                            value="{{ old('settings.hero_video_url', $settings['hero_video_url'] ?? '') }}"
                            placeholder="https://cdn.example.com/acons-hero.mp4"
                        >
                        <div class="form-text">Để trống nếu muốn dùng ảnh dự án. URL cần trỏ trực tiếp tới file MP4 có thể truy cập công khai.</div>
                        @error('settings.hero_video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </section>

                <section class="card card-outline card-primary mb-4" id="seo-settings">
                    <div class="card-header">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">SEO mặc định</h2>
                            <div class="small text-secondary">Dùng cho Trang chủ và làm giá trị dự phòng khi một trang chưa có tiêu đề hoặc mô tả riêng.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="default_meta_title" class="form-label fw-semibold">Meta title mặc định</label>
                            <input
                                id="default_meta_title"
                                name="settings[default_meta_title]"
                                class="form-control @error('settings.default_meta_title') is-invalid @enderror"
                                value="{{ old('settings.default_meta_title', $settings['default_meta_title'] ?? '') }}"
                                maxlength="70"
                            >
                            <div class="form-text">Tối đa 70 ký tự. Nên đặt tên thương hiệu và lĩnh vực chính.</div>
                            @error('settings.default_meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="default_meta_description" class="form-label fw-semibold">Meta description mặc định</label>
                            <textarea
                                id="default_meta_description"
                                name="settings[default_meta_description]"
                                class="form-control @error('settings.default_meta_description') is-invalid @enderror"
                                rows="3"
                                maxlength="170"
                            >{{ old('settings.default_meta_description', $settings['default_meta_description'] ?? '') }}</textarea>
                            <div class="form-text">Tối đa 170 ký tự, mô tả ngắn gọn dịch vụ và điểm mạnh của ACONS.</div>
                            @error('settings.default_meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="card card-outline card-primary mb-4" id="social-settings">
                    <div class="card-header">
                        <div>
                            <h2 class="h5 fw-semibold mb-1">Mạng xã hội</h2>
                            <div class="small text-secondary">Liên kết có dữ liệu sẽ xuất hiện trong footer website.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach([
                                ['facebook_url', 'Facebook', 'https://facebook.com/...'],
                                ['instagram_url', 'Instagram', 'https://instagram.com/...'],
                                ['youtube_url', 'YouTube', 'https://youtube.com/...'],
                            ] as [$key, $label, $placeholder])
                                <div class="col-md-4">
                                    <label for="{{ $key }}" class="form-label fw-semibold">{{ $label }}</label>
                                    <input
                                        type="url"
                                        id="{{ $key }}"
                                        name="settings[{{ $key }}]"
                                        class="form-control @error('settings.'.$key) is-invalid @enderror"
                                        value="{{ old('settings.'.$key, $settings[$key] ?? '') }}"
                                        placeholder="{{ $placeholder }}"
                                    >
                                    @error('settings.'.$key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            <aside class="col-xl-4">
                <div class="card admin-sticky-card">
                    <div class="card-header">
                        <h2 class="card-title fw-semibold mb-0">Các nhóm cấu hình</h2>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#branding-settings" class="list-group-item list-group-item-action"><i class="bi bi-building me-2"></i>Thương hiệu</a>
                        <a href="#contact-settings" class="list-group-item list-group-item-action"><i class="bi bi-telephone me-2"></i>Thông tin liên hệ</a>
                        <a href="#homepage-media-settings" class="list-group-item list-group-item-action"><i class="bi bi-play-btn me-2"></i>Media Trang chủ</a>
                        <a href="#seo-settings" class="list-group-item list-group-item-action"><i class="bi bi-search me-2"></i>SEO mặc định</a>
                        <a href="#social-settings" class="list-group-item list-group-item-action"><i class="bi bi-share me-2"></i>Mạng xã hội</a>
                    </div>
                    <div class="card-body border-top">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-floppy me-1"></i> Lưu cấu hình
                        </button>
                        <p class="small text-secondary text-center mb-0 mt-2">Các thay đổi có hiệu lực ngay sau khi lưu.</p>
                    </div>
                </div>
            </aside>
        </div>
    </form>
@endsection
