@extends('layouts.admin')
@section('page_title', 'Sửa dự án')
@section('page_header', 'Sửa: '.$project->title)
@section('page_actions')<a class="btn btn-outline-secondary" href="{{ route('projects.show', $project) }}" target="_blank">Xem trang</a>@endsection
@section('page_content')
<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.projects._form')</form>
@if($project->images->isNotEmpty())<div class="card mt-4"><div class="card-header"><h2 class="card-title">Ảnh đã tải lên</h2></div><div class="card-body"><div class="row g-3">@foreach($project->images as $image)<div class="col-6 col-md-3"><img src="{{ asset('storage/'.$image->path) }}" alt="" class="w-100 object-fit-cover rounded" style="aspect-ratio:4/3"><div class="d-flex justify-content-between mt-2"><span class="small text-secondary">{{ $image->type === 'floor_plan' ? 'Mặt bằng' : 'Gallery' }}</span><form action="{{ route('admin.project-images.destroy', $image) }}" method="POST" onsubmit="return confirm('Xóa ảnh này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button></form></div></div>@endforeach</div></div></div>@endif
@endsection
