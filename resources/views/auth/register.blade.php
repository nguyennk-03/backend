@extends('layouts.app')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <a href="index.html">
                                    <img src="{{asset('images/logo-sm.png')  }}" alt="Logo" height="40">
                                </a>
                            </div>
                            <h4 class="text-center text-primary mb-3">Create Account</h4>

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
                                    <label for="name" class="form-label">Full Name</label>
                                    <input class="form-control" name="name" type="text" id="name"
                                        placeholder="Enter your name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email </label>
                                    <input class="form-control" name="email" type="email" id="email" required
                                        placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input class="form-control" name="password" type="password" required id="password"
                                        placeholder="Enter your password">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input class="form-control" type="password" name="password_confirmation" required
                                        id="password_confirmation" placeholder="Enter your password">
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signup" name="terms">
                                    <label class="form-check-label" for="checkbox-signup">
                                        I accept <a href="#" class="text-primary">Terms and Conditions</a>
                                    </label>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary w-100" type="submit">Sign Up</button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <h5 class="text-muted">Or sign up using</h5>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-facebook"></i></a>
                                    <a href="#" class="btn btn-outline-danger btn-sm"><i class="mdi mdi-google"></i></a>
                                    <a href="#" class="btn btn-outline-info btn-sm"><i class="mdi mdi-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm"><i
                                            class="mdi mdi-github-circle"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p class="text-muted">Already have an account?
                            <a href="{{route('login')  }}" class="fw-bold text-primary">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection