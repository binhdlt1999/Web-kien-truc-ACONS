@extends('layouts.app')
@section('title', 'Dự án | ACONS')
@section('meta_description', 'Khám phá các dự án kiến trúc, nội thất và xây dựng tiêu biểu của ACONS.')

@section('content')
<header class="page-hero"><div class="container"><div class="eyebrow text-white-50">Portfolio</div><h1 class="display-title display-3">Dự án ACONS</h1></div></header>
<section class="section-space">
    <div class="container">
        <form class="row g-3 align-items-end mb-5" method="GET">
            <div class="col-md-5"><label class="form-label" for="q">Tìm dự án</label><input class="form-control" id="q" name="q" value="{{ request('q') }}" placeholder="Tên, địa điểm, phong cách..."></div>
            <div class="col-md-4"><label class="form-label" for="category">Danh mục</label><select class="form-select" id="category" name="category"><option value="">Tất cả danh mục</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-md-3"><button class="btn btn-acons w-100">Tìm kiếm</button></div>
        </form>
        <div class="row g-4">@forelse($projects as $project)<div class="col-md-6 col-xl-4"><x-project-card :project="$project" /></div>@empty<div class="col-12"><p>Không tìm thấy dự án phù hợp.</p></div>@endforelse</div>
        <div class="mt-5">{{ $projects->links() }}</div>
    </div>
</section>
@endsection
