@extends('layouts.app')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <a href="index.html">
                                    <img src="assets/images/logo-light.png" alt="Logo" height="40">
                                </a>
                            </div>
                            <h4 class="text-center text-primary mb-3 fw-bold">Sign In</h4>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control rounded-pill" type="email" name="email" id="emailaddress"
                                        required placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input class="form-control rounded-pill" type="password" name="password" required
                                        id="password" placeholder="Enter your password">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                    <a href="pages-recoverpw.html" class="text-muted small">Forgot password?</a>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary w-100 rounded-pill" type="submit">Log In</button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <h5 class="text-muted">Or sign in with</h5>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-circle p-2"><i
                                            class="bi bi-facebook"></i></a>
                                    <a href="#" class="btn btn-outline-danger btn-sm rounded-circle p-2"><i
                                            class="bi bi-google"></i></a>
                                    <a href="#" class="btn btn-outline-info btn-sm rounded-circle p-2"><i
                                            class="bi bi-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle p-2"><i
                                            class="bi bi-github"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-1 text-muted">Don't have an account? <a href={{ route('register') }}
                                class="fw-bold text-primary">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection