@extends('layouts.app')

@section('content')
<div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">

                            <div class="card-body p-4">
                            
                                <div class="text-center w-75 m-auto">
                                    <a href="index.html">
                                        <span><img src="assets\images\logo-light.png" alt="" height="26"></span>
                                    </a>
                                </div>

                                <div class="text-center">
                                    <div class="mt-4">
                                        <div class="logout-checkmark">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 130.2 130.2">
                                                <circle class="path circle" fill="none" stroke="#4bd396" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"></circle>
                                                <polyline class="path check" fill="none" stroke="#4bd396" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                                            </svg>
                                        </div>
                                    </div>

                                    <h3>See you again !</h3>

                                    <p class="text-muted font-13"> You are now successfully sign out. </p>
                                </div>

                            </div> <!-- end card-body -->
        
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">Back to <a href="pages-login.html" class="text-muted ml-1"><b class="font-weight-semibold">Sign In</b></a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
    
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
@endsection
