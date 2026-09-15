@extends('layouts.admin')
@section('page_title', 'Thêm dự án')
@section('page_header', 'Thêm dự án mới')
@section('page_actions')<a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Quay lại</a>@endsection
@section('page_content')<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('admin.projects._form')</form>@endsection
