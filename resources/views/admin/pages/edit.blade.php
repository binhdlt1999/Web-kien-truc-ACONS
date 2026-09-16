@extends('layouts.admin')

@section('page_title', 'Nội dung '.$pageTitle)
@section('page_header', 'Chỉnh sửa nội dung '.$pageTitle)

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Trung tâm nội dung</a>
        <a href="{{ route($previewRoute) }}" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-eye me-1"></i> Xem trang public</a>
    </div>
@endsection

@section('page_content')
    <div class="alert alert-light border d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-info-circle text-primary fs-4"></i>
        <div><strong>{{ $pageTitle }}</strong><div class="small text-secondary mt-1">{{ $pageDescription }}</div></div>
    </div>

    <form action="{{ route('admin.pages.update', $pageKey) }}" method="POST">
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
                            @php
                                $editorGroups = collect($section['fields'])->groupBy(function (array $field): string {
                                    if (str_contains($field['label'], ' · ')) {
                                        return (string) str($field['label'])->before(' · ');
                                    }

                                    return str_contains($field['key'], '_button') ? 'Nút hành động' : 'Nội dung chính';
                                });
                            @endphp
                            <div class="d-grid gap-3">
                                @foreach($editorGroups as $groupTitle => $groupFields)
                                    @php
                                        $textFieldCount = $groupFields->where('type', 'text')->count();
                                        $hasTextarea = $groupFields->contains(fn (array $field): bool => $field['type'] === 'textarea');
                                    @endphp
                                    <section
                                        class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4"
                                        data-page-editor-group="{{ $pageKey }}-{{ $section['key'] }}-{{ str($groupTitle)->slug() }}"
                                    >
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge rounded-pill text-bg-light border text-primary">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                <h3 class="h6 fw-semibold mb-0">{{ $groupTitle }}</h3>
                                            </div>
                                            <span class="small text-secondary">{{ $groupFields->count() }} trường nội dung</span>
                                        </div>
                                        <div class="row g-3 align-items-start">
                                            @foreach($groupFields as $field)
                                                @php
                                                    $fieldColumn = match (true) {
                                                        $field['type'] === 'textarea' && $groupFields->count() === 2 => 'col-md-8',
                                                        $field['type'] === 'textarea' => 'col-12',
                                                        $textFieldCount >= 3 => 'col-lg-4',
                                                        $textFieldCount === 1 && $hasTextarea => 'col-md-4',
                                                        default => 'col-md-6',
                                                    };
                                                    $fieldLabel = str_contains($field['label'], ' · ')
                                                        ? str($field['label'])->after(' · ')
                                                        : $field['label'];
                                                @endphp
                                                @include('admin.pages.partials.content-field', [
                                                    'field' => $field,
                                                    'label' => $fieldLabel,
                                                    'columnClass' => $fieldColumn,
                                                    'rows' => 3,
                                                ])
                                            @endforeach
                                        </div>
                                    </section>
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
                        <p class="small text-secondary"><i class="bi bi-shield-check me-1"></i> Chỉ nội dung chữ được cập nhật. Bố cục và mã nguồn của trang vẫn được bảo vệ.</p>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy me-1"></i> Lưu toàn bộ thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
