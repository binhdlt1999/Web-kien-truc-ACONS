<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Dashboard') | ACONS Admin</title>
    @vite(['resources/css/adminlte.css', 'resources/js/adminlte.js'])
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body shadow-sm">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Thu gọn thanh điều hướng">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block"><a href="{{ route('home') }}" target="_blank" class="nav-link">Xem website</a></li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2 text-secondary small">{{ auth()->user()->name }}</li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar text-bg-dark shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link text-decoration-none">
                <span class="brand-text fw-semibold">ACONS ADMIN</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">TỔNG QUAN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                            <i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-header">NỘI DUNG</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.projects.index') }}" class="nav-link @if(request()->routeIs('admin.projects.*')) active @endif">
                            <i class="nav-icon bi bi-buildings"></i><p>Dự án</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link @if(request()->routeIs('admin.categories.*')) active @endif">
                            <i class="nav-icon bi bi-tags"></i><p>Danh mục</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.services.index') }}" class="nav-link @if(request()->routeIs('admin.services.*')) active @endif">
                            <i class="nav-icon bi bi-grid"></i><p>Dịch vụ</p>
                        </a>
                    </li>
                    <li class="nav-header">KHÁCH HÀNG</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}" class="nav-link @if(request()->routeIs('admin.contacts.*')) active @endif">
                            <i class="nav-icon bi bi-chat-left-text"></i><p>Yêu cầu tư vấn</p>
                        </a>
                    </li>
                    <li class="nav-header">HỆ THỐNG</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.edit') }}" class="nav-link @if(request()->routeIs('admin.settings.*')) active @endif">
                            <i class="nav-icon bi bi-gear"></i><p>Cấu hình website</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <h1 class="mb-0 fs-3">@yield('page_header', 'Dashboard')</h1>
                    <div>@yield('page_actions')</div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Vui lòng kiểm tra lại dữ liệu.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @yield('page_content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Laravel 12 + AdminLTE 4</div>
        <strong>© {{ date('Y') }} ACONS.</strong> All rights reserved.
    </footer>
</div>
@stack('scripts')
</body>
</html>
