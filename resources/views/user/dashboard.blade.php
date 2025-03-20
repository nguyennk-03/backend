@extends('layouts.app')
@section('title', 'Bảng điều khiển')
@section('content')
    <div class="container mt-5">
        <h2>Bảng điều khiển</h2>
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <p>Xin chào, {{ $user->name }}</p>
        @if ($user->role === 'admin')
            <a href="{{ route('admin') }}" class="btn btn-primary">Đi đến trang Quản trị</a>
        @endif
    </div>
@endsection