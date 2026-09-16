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
                            @if($section['key'] === 'hero')
                                <div class="d-grid gap-4">
                                    @foreach(range(1, 3) as $slideNumber)
                                        @php
                                            $slideFields = collect($section['fields'])
                                                ->filter(fn (array $field): bool => str_starts_with($field['key'], 'home_hero_'.$slideNumber.'_'));
                                        @endphp
                                        <section id="hero-slide-{{ $slideNumber }}" class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-hero-slide-editor="{{ $slideNumber }}">
                                            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                                <div>
                                                    <span class="badge text-bg-primary mb-2">Slide {{ $slideNumber }}</span>
                                                    <h3 class="h5 mb-0">Nội dung slide {{ $slideNumber }}</h3>
                                                </div>
                                                <span class="small text-secondary">{{ $slideFields->count() }} trường nội dung</span>
                                            </div>
                                            <div class="row g-3">
                                                @foreach($slideFields as $field)
                                                    @include('admin.pages.partials.content-field', [
                                                        'field' => $field,
                                                        'label' => str($field['label'])->after(' · '),
                                                        'columnClass' => $field['type'] === 'textarea' ? 'col-12' : 'col-lg-4',
                                                    ])
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            @elseif($section['key'] === 'achievements')
                                <div class="d-grid gap-3">
                                    @foreach(range(1, 5) as $statNumber)
                                        @php
                                            $statFields = collect($section['fields'])
                                                ->filter(fn (array $field): bool => str_starts_with($field['key'], 'home_stat_'.$statNumber.'_'));
                                        @endphp
                                        <section id="achievement-{{ $statNumber }}" class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-achievement-editor="{{ $statNumber }}">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span class="badge rounded-pill text-bg-light border text-primary">{{ str_pad((string) $statNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                                <h3 class="h6 fw-semibold mb-0">Chỉ số {{ $statNumber }}</h3>
                                            </div>
                                            <div class="row g-3">
                                                @foreach($statFields as $field)
                                                    @php
                                                        $fieldColumn = match (true) {
                                                            str_ends_with($field['key'], '_value') => 'col-md-3',
                                                            str_ends_with($field['key'], '_label') => 'col-md-4',
                                                            default => 'col-md-5',
                                                        };
                                                    @endphp
                                                    @include('admin.pages.partials.content-field', [
                                                        'field' => $field,
                                                        'label' => str($field['label'])->after(' · '),
                                                        'columnClass' => $fieldColumn,
                                                    ])
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            @elseif($section['key'] === 'section_headings')
                                @php
                                    $headingGroups = [
                                        'services' => 'Khu vực Dịch vụ',
                                        'projects' => 'Khu vực Dự án',
                                        'difference' => 'Khu vực Khác biệt',
                                        'innovation' => 'Khu vực Đổi mới',
                                        'cases' => 'Khu vực Dự án điển hình',
                                    ];
                                @endphp
                                <div class="d-grid gap-3">
                                    @foreach($headingGroups as $groupKey => $groupTitle)
                                        @php
                                            $groupFields = collect($section['fields'])
                                                ->filter(fn (array $field): bool => str_starts_with($field['key'], 'home_'.$groupKey.'_'));
                                        @endphp
                                        <section class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-section-heading-editor="{{ $groupKey }}">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span class="badge rounded-pill text-bg-light border text-primary">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                <h3 class="h6 fw-semibold mb-0">{{ $groupTitle }}</h3>
                                            </div>
                                            <div class="row g-3">
                                                @foreach($groupFields as $field)
                                                    @include('admin.pages.partials.content-field', [
                                                        'field' => $field,
                                                        'label' => str($field['label'])->after(' · '),
                                                        'columnClass' => $field['type'] === 'textarea' ? 'col-12' : 'col-md-6',
                                                    ])
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            @elseif(in_array($section['key'], ['difference', 'innovation'], true))
                                @php
                                    $groupCount = 4;
                                    $groupPrefix = $section['key'] === 'difference' ? 'home_difference_' : 'home_innovation_';
                                    $groupLabel = $section['key'] === 'difference' ? 'Điểm khác biệt' : 'Năng lực';
                                @endphp
                                <div class="d-grid gap-3">
                                    @foreach(range(1, $groupCount) as $itemNumber)
                                        @php
                                            $itemFields = collect($section['fields'])
                                                ->filter(fn (array $field): bool => str_starts_with($field['key'], $groupPrefix.$itemNumber.'_'));
                                        @endphp
                                        <section class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-{{ str_replace('_', '-', $section['key']) }}-editor="{{ $itemNumber }}">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span class="badge rounded-pill text-bg-light border text-primary">{{ str_pad((string) $itemNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                                <h3 class="h6 fw-semibold mb-0">{{ $groupLabel }} {{ $itemNumber }}</h3>
                                            </div>
                                            <div class="row g-3 align-items-start">
                                                @foreach($itemFields as $field)
                                                    @include('admin.pages.partials.content-field', [
                                                        'field' => $field,
                                                        'label' => str($field['label'])->after(' · '),
                                                        'columnClass' => $field['type'] === 'textarea' ? 'col-md-8' : 'col-md-4',
                                                        'rows' => 2,
                                                    ])
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            @elseif($section['key'] === 'contact_cta')
                                @php
                                    $ctaContentFields = collect($section['fields'])
                                        ->reject(fn (array $field): bool => str_contains($field['key'], '_button'));
                                    $ctaButtonFields = collect($section['fields'])
                                        ->filter(fn (array $field): bool => str_contains($field['key'], '_button'));
                                @endphp
                                <div class="d-grid gap-3">
                                    <section class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-contact-cta-editor="content">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <span class="badge rounded-pill text-bg-light border text-primary">01</span>
                                            <h3 class="h6 fw-semibold mb-0">Nội dung chính</h3>
                                        </div>
                                        <div class="row g-3">
                                            @foreach($ctaContentFields as $field)
                                                @include('admin.pages.partials.content-field', [
                                                    'field' => $field,
                                                    'columnClass' => $field['type'] === 'textarea' ? 'col-12' : 'col-lg-4',
                                                ])
                                            @endforeach
                                        </div>
                                    </section>
                                    <section class="border border-secondary-subtle rounded-3 bg-body-tertiary p-3 p-lg-4" data-contact-cta-editor="buttons">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <span class="badge rounded-pill text-bg-light border text-primary">02</span>
                                            <h3 class="h6 fw-semibold mb-0">Nút hành động</h3>
                                        </div>
                                        <div class="row g-3">
                                            @foreach($ctaButtonFields as $field)
                                                @include('admin.pages.partials.content-field', [
                                                    'field' => $field,
                                                    'columnClass' => 'col-md-6',
                                                ])
                                            @endforeach
                                        </div>
                                    </section>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($section['fields'] as $field)
                                        @include('admin.pages.partials.content-field', ['field' => $field])
                                    @endforeach
                                </div>
                            @endif
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
