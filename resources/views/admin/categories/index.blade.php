@extends('layouts.admin')
@section('page_title', 'Danh mục')
@section('page_header', 'Danh mục dự án')
@section('page_actions')<a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm danh mục</a>@endsection
@section('page_content')<div class="card"><table class="table table-hover mb-0"><thead><tr><th>Tên</th><th>Slug</th><th>Dự án</th><th></th></tr></thead><tbody>@foreach($categories as $category)<tr><td>{{ $category->name }}</td><td>{{ $category->slug }}</td><td>{{ $category->projects_count }}</td><td class="text-end"><a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Sửa</a><form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Xóa danh mục?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form></td></tr>@endforeach</tbody></table></div>@endsection
