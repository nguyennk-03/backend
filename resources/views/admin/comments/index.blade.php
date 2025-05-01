<?php

use Illuminate\Support\Str;
?>

<!-- Kế thừa layout admin để sử dụng cấu trúc giao diện cơ bản -->
@extends('admin.layout')

<!-- Đặt tiêu đề trang -->
@section('title', 'Bình luận')

<!-- Xác định nội dung chính của trang -->
@section('content')
<div class="container-fluid">
    <!-- Phần tiêu đề trang -->
    <div class="row">
        <div class="col-12">
            <!-- Hiển thị tiêu đề trang và điều hướng breadcrumb -->
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-comments me-2"></i>Quản Lý Bình Luận</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Bình luận</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Thông báo thành công -->
    <!-- Hiển thị thông báo thành công nếu có trong session -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Phần lọc và thao tác -->
    <div class="card shadow-sm rounded-lg mb-3">
        <div class="card-body">
            <!-- Form để lọc bình luận theo sản phẩm, trạng thái hiển thị và tìm kiếm -->
            <form action="{{ route('binh-luan.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Lọc theo sản phẩm -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-box me-1"></i> Sản phẩm</label>
                        <select name="product_id" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Lọc theo trạng thái hiển thị -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-eye me-1"></i> Hiển thị</label>
                        <select name="is_hidden" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            <option value="0" {{ request('is_hidden') === '0' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="1" {{ request('is_hidden') === '1' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    <!-- Tìm kiếm theo nội dung bình luận -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-search me-1"></i> Tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-sm border-0 shadow-sm" placeholder="Tìm theo nội dung bình luận" value="{{ request('search') }}">
                    </div>
                    <!-- Các nút thao tác -->
                    <div class="col-md-12 d-flex gap-2 justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('binh-luan.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng bình luận -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <!-- Bảng hiển thị danh sách bình luận -->
                <table id="CommentTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Sản phẩm</th>
                            <th class="text-center py-3">Người dùng</th>
                            <th class="text-center py-3">Nội dung</th>
                            <th class="text-center py-3">Bình luận cha</th>
                            <th class="text-center py-3">Hiển thị</th>
                            <th class="text-center py-3">Ngày tạo</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                        <tr>
                            <td class="text-center">{{ $comment->id }}</td>
                            <td>{{ $comment->product->name ?? 'N/A' }}</td>
                            <td>{{ $comment->user->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($comment->message, 50) }}</td>
                            <td class="text-center">{{ $comment->parent ? $comment->parent->id : 'N/A' }}</td>
                            <td class="text-center">
                                {{ $comment->is_hidden ? 'Ẩn' : 'Hiển thị' }}
                            </td>
                            <td class="text-center">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('binh-luan.show', $comment->id) }}" class="btn btn-info btn-sm shadow-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <!-- Hiển thị thông báo nếu không có bình luận -->
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không có bình luận nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection