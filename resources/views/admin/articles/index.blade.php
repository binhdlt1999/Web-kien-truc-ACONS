@extends('layouts.admin')

@section('page_title', 'Bài viết')
@section('page_header', 'Quản lý bài viết')
@section('page_description', 'Biên tập nội dung kiến thức và tài nguyên xuất hiện trên website.')

@section('page_actions')
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Thêm bài viết</a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="card-title fw-semibold mb-0">Danh sách bài viết</h2>
            <span class="badge text-bg-light border">{{ $articles->total() }} bài viết</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Bài viết</th><th>Trạng thái</th><th>Ngày xuất bản</th><th>Cập nhật</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $article->cover_image ? asset('storage/'.$article->cover_image) : asset('images/project-placeholder.svg') }}" alt="" class="admin-media-thumb">
                                    <div><strong>{{ $article->title }}</strong><div class="small text-secondary">/{{ $article->slug }}</div></div>
                                </div>
                            </td>
                            <td>
                                @if($article->status === 'draft')
                                    <span class="badge text-bg-secondary">Bản nháp</span>
                                @elseif($article->published_at?->isFuture())
                                    <span class="badge text-bg-info">Đã lên lịch</span>
                                @else
                                    <span class="badge text-bg-success">Đã xuất bản</span>
                                @endif
                            </td>
                            <td>{{ $article->published_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $article->updated_at->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                @if($article->status === 'published' && $article->published_at?->isPast())
                                    <a href="{{ route('articles.show', $article) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="Xem bài viết"><i class="bi bi-eye"></i></a>
                                @endif
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">Chưa có bài viết. Hãy tạo bài viết đầu tiên.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
            <div class="card-footer">{{ $articles->links() }}</div>
        @endif
    </div>
@endsection
