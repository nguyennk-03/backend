<?php

use Illuminate\Support\Str;
?>

@extends('admin.layout')
@section('title', 'Bài viết')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-newspaper-o me-2"></i>Quản Lý Bài Viết</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Bài viết</li>
                </ol>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filter and Action Section -->
    <div class="card shadow-sm rounded-lg mb-3">
        <div class="card-body">
            <form action="{{ route('bai-viet.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-list-ul me-1"></i> Danh mục</label>
                        <select name="category_id" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-tag me-1"></i> Thương hiệu</label>
                        <select name="brand_id" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2 align-items-end justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('bai-viet.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                        <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                            <i class="fas fa-plus me-1"></i> Thêm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- News Table -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="NewsTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Tiêu đề</th>
                            <th class="text-center py-3">Danh mục</th>
                            <th class="text-center py-3">Thương hiệu</th>
                            <th class="text-center py-3">Tác giả</th>
                            <th class="text-center py-3">Lượt xem</th>
                            <th class="text-center py-3">Ngày tạo</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $newsItem)
                        <tr>
                            <td class="text-center">{{ $newsItem->id }}</td>
                            <td>{{ Str::limit($newsItem->title, 50) }}</td>
                            <td>{{ $newsItem->category->name ?? 'N/A' }}</td>
                            <td>{{ $newsItem->brand->name ?? 'N/A' }}</td>
                            <td>{{ $newsItem->author }}</td>
                            <td class="text-center">{{ $newsItem->views }}</td>
                            <td class="text-center">{{ $newsItem->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#editNewsModal{{ $newsItem->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('bai-viet.destroy', $newsItem->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không có bài viết nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add News Modal -->
    <div class="modal fade" id="addNewsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addNewsModalLabel"><i class="fas fa-plus-circle me-2"></i> Thêm bài viết mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('bai-viet.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="title" id="title" class="form-control border-0 shadow-sm" value="{{ old('title') }}" placeholder="Nhập tiêu đề bài viết" required>
                            @error('title')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-select border-0 shadow-sm" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="brand_id" class="form-label fw-semibold">Thương hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-select border-0 shadow-sm">
                                <option value="">-- Không chọn --</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label fw-semibold">Tác giả</label>
                            <input type="text" name="author" id="author" class="form-control border-0 shadow-sm" value="{{ old('author') }}" placeholder="Nhập tên tác giả" required>
                            @error('author')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Hình ảnh</label>
                            <input type="file" name="image" id="image" class="form-control border-0 shadow-sm" accept="image/*">
                            @error('image')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">Nội dung</label>
                            <textarea name="content" id="content" class="form-control border-0 shadow-sm" rows="6" placeholder="Nhập nội dung bài viết" required>{{ old('content') }}</textarea>
                            @error('content')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu bài viết</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit News Modals -->
    @foreach ($news as $newsItem)
    <div class="modal fade" id="editNewsModal{{ $newsItem->id }}" tabindex="-1" aria-labelledby="editNewsModalLabel{{ $newsItem->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editNewsModalLabel{{ $newsItem->id }}"><i class="fas fa-edit me-2"></i> Chỉnh sửa bài viết #{{ $newsItem->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('bai-viet.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title_{{ $newsItem->id }}" class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="title" id="title_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" value="{{ old('title', $newsItem->title) }}" placeholder="Nhập tiêu đề bài viết" required>
                            @error('title')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id_{{ $newsItem->id }}" class="form-label fw-semibold">Danh mục</label>
                            <select name="category_id" id="category_id_{{ $newsItem->id }}" class="form-select border-0 shadow-sm" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $newsItem->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="brand_id_{{ $newsItem->id }}" class="form-label fw-semibold">Thương hiệu</label>
                            <select name="brand_id" id="brand_id_{{ $newsItem->id }}" class="form-select border-0 shadow-sm">
                                <option value="">-- Không chọn --</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $newsItem->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author_{{ $newsItem->id }}" class="form-label fw-semibold">Tác giả</label>
                            <input type="text" name="author" id="author_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" value="{{ old('author', $newsItem->author) }}" placeholder="Nhập tên tác giả" required>
                            @error('author')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image_{{ $newsItem->id }}" class="form-label fw-semibold">Hình ảnh</label>
                            <input type="file" name="image" id="image_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" accept="image/*">
                            @if($newsItem->image)
                            <img src="{{ asset('storage/' . $newsItem->image) }}" alt="Current image" class="mt-2" style="max-width: 100px;">
                            @endif
                            @error('image')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content_{{ $newsItem->id }}" class="form-label fw-semibold">Nội dung</label>
                            <textarea name="content" id="content_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" rows="6" placeholder="Nhập nội dung bài viết" required>{{ old('content', $newsItem->content) }}</textarea>
                            @error('content')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection