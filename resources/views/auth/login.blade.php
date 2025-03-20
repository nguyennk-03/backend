@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h4 class="text-primary fw-bold">Đăng Nhập</h4>
                            </div>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email</label>
                                    <input class="form-control rounded-pill" type="email" name="email" id="email" required
                                        placeholder="Nhập email của bạn">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input class="form-control rounded-pill" type="password" name="password" required
                                        id="password" placeholder="Nhập mật khẩu">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                        <label class="form-check-label" for="checkbox-signin">Ghi nhớ đăng nhập</label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="text-muted small">Quên mật khẩu?</a>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary w-100 rounded-pill" type="submit">Đăng Nhập</button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <h5 class="text-muted">Hoặc đăng nhập bằng</h5>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i
                                            class="bi bi-facebook"></i></a>
                                    <a href="#" class="btn btn-outline-danger btn-sm rounded-circle"><i
                                            class="bi bi-google"></i></a>
                                    <a href="#" class="btn btn-outline-info btn-sm rounded-circle"><i
                                            class="bi bi-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i
                                            class="bi bi-github"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-1 text-muted">Chưa có tài khoản?
                                <a href="{{ route('register') }}" class="fw-bold text-primary">Đăng ký ngay</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection