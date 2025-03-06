@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Dashboard</h1>
                <p>Welcome to your dashboard user, {{ Auth::user()->name }}!</p>
            </div>
        </div>
    </div>
@endsection