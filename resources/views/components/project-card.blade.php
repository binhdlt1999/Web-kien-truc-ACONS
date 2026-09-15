@props(['project'])
@php($cover = $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/project-placeholder.svg'))
<article class="project-card h-100">
    <a href="{{ route('projects.show', $project) }}" class="d-block h-100" aria-label="Xem dự án {{ $project->title }}">
        <img src="{{ $cover }}" alt="{{ $project->title }}" loading="lazy" width="800" height="600">
        <div class="project-card-overlay">
            <div class="small text-uppercase opacity-75">{{ $project->category->name }}</div>
            <h3 class="h4 mb-1">{{ $project->title }}</h3>
            @if($project->location)<div class="small">{{ $project->location }}</div>@endif
        </div>
    </a>
</article>
