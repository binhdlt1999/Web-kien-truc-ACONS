@extends('layouts.app')
@section('title', $article->title.' | ACONS')
@section('meta_description', $article->excerpt ?: $article->title)
@section('content')
<header class="page-hero" data-reveal="fade"><div class="container"><div class="eyebrow text-white-50">Góc kiến thức · {{ $article->published_at->format('d/m/Y') }}</div><h1 class="display-title display-3">{{ $article->title }}</h1></div></header>
@if($article->cover_image)<img class="project-detail-cover" src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title }}" data-reveal="fade">@endif
<article class="section-space" data-reveal="fade-up"><div class="container"><div class="row justify-content-center"><div class="col-lg-8"><p class="lead">{{ $article->excerpt }}</p><div class="content-copy">{!! nl2br(e($article->body)) !!}</div></div></div></div></article>
@endsection
