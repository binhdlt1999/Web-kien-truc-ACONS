@extends('layouts.admin')
@section('page_title', 'Dự án')
@section('page_header', 'Quản lý dự án')
@section('page_actions')<a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm dự án</a>@endsection

@section('page_content')
<div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Dự án</th><th>Danh mục</th><th>Trạng thái</th><th>Nổi bật</th><th>Lượt xem</th><th class="text-end">Thao tác</th></tr></thead><tbody>
@forelse($projects as $project)<tr><td><div class="d-flex align-items-center gap-3"><img src="{{ $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg') }}" alt="" width="72" height="54" class="object-fit-cover rounded"><div><strong>{{ $project->title }}</strong><div class="small text-secondary">{{ $project->location }}</div></div></div></td><td>{{ $project->category->name }}</td><td><span class="badge text-bg-{{ $project->status === 'published' ? 'success' : 'secondary' }}">{{ $project->status === 'published' ? 'Đã đăng' : 'Bản nháp' }}</span></td><td>{{ $project->is_featured ? 'Có' : 'Không' }}</td><td>{{ number_format($project->view_count) }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.projects.edit', $project) }}">Sửa</a><form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa dự án này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form></td></tr>
@empty<tr><td colspan="6" class="text-center py-5">Chưa có dự án.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">{{ $projects->links() }}</div></div>
@endsection
