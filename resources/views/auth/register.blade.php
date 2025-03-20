@extends('layouts.app')
@section('title', 'Đăng ký')
@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <!-- Di chuyển header ra khỏi form -->
                            <h4 class="text-center text-primary mb-3">Tạo tài khoản</h4>
                            @if (session('message'))
                                <div class="alert alert-success">{{ session('message') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input class="form-control" name="name" type="text" id="name"
                                        placeholder="Nhập họ và tên của bạn" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control" name="email" type="email" id="email" required
                                        placeholder="Nhập email của bạn">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input class="form-control" name="password" type="password" required id="password"
                                        placeholder="Nhập mật khẩu của bạn">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                    <input class="form-control" type="password" name="password_confirmation" required
                                        id="password_confirmation" placeholder="Nhập lại mật khẩu của bạn">
                                </div>

                                <div class="mb-3">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Tôi đồng ý với <a href="#" class="text-primary">Điều khoản và Điều kiện</a>
                                    </label>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary w-100" type="submit">Đăng ký</button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <h5 class="text-muted">Hoặc đăng ký bằng</h5>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-google"></i></a>
                                    <a href="#" class="btn btn-outline-info btn-sm rounded-circle"><i class="bi bi-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-github"></i></a>
                                </div>
                            </div>
                            </div>

                            <div class="text-center mt-3">
                                <p class="text-muted">Đã có tài khoản?
                                    <a href="{{ route('login') }}" class="fw-bold text-primary">Đăng nhập</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection