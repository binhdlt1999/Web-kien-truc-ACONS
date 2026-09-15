<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập quản trị | ACONS</title>
    @vite(['resources/css/adminlte.css', 'resources/js/adminlte.js'])
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo"><a href="{{ route('home') }}"><b>ACONS</b> Admin</a></div>
        <div class="card shadow">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Đăng nhập khu vực quản trị</p>
                @if($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
                <form action="{{ route('admin.login.store') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required autofocus autocomplete="username">
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required autocomplete="current-password">
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-7"><div class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Ghi nhớ</label></div></div>
                        <div class="col-5"><button type="submit" class="btn btn-primary w-100">Đăng nhập</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
