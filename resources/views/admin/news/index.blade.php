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
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="py-3">Tiêu đề</th>
                            <th class="py-3">Slug</th>
                            <th class="py-3">Danh mục</th>
                            <th class="py-3">Thương hiệu</th>
                            <th class="py-3">Tác giả</th>
                            <th class="text-center py-3">Trạng thái</th>
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
                            <td>{{ $newsItem->slug }}</td>
                            <td>{{ $newsItem->category->name ?? 'N/A' }}</td>
                            <td>{{ $newsItem->brand->name ?? 'N/A' }}</td>
                            <td>{{ $newsItem->author }}</td>
                            <td class="text-center">
                                @if($newsItem->status)
                                <span class="badge bg-success">Hiển thị</span>
                                @else
                                <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $newsItem->views }}</td>
                            <td class="text-center">{{ $newsItem->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#showNewsModal{{ $newsItem->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#editNewsModal{{ $newsItem->id }}">
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
                            <td colspan="10" class="text-center text-muted py-4">Không có bài viết nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add News Modal -->
    <div class="modal fade" id="addNewsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-primary" id="addNewsModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Thêm bài viết mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-4">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('bai-viet.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tiêu đề</label>
                                <input type="text" name="title" class="form-control shadow-sm" placeholder="Nhập tiêu đề bài viết" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tác giả</label>
                                <input type="text" name="author" class="form-control shadow-sm" placeholder="Nhập tên tác giả" value="{{ old('author') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Danh mục</label>
                                <select name="category_id" class="form-select shadow-sm" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thương hiệu</label>
                                <select name="brand_id" class="form-select shadow-sm">
                                    <option value="">-- Không chọn --</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Hình ảnh</label>
                                <input type="file" name="image" class="form-control shadow-sm" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nội dung</label>
                                <textarea name="content" class="form-control shadow-sm" rows="6" placeholder="Nhập nội dung bài viết" required>{{ old('content') }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer border-0 mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Lưu bài viết
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($news as $newsItem)

    <!-- Show News Modal -->
    <!-- Show News Modal -->
    <div class="modal fade" id="showNewsModal{{ $newsItem->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showNewsModalLabel{{ $newsItem->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-5 shadow-lg border-0 bg-white">
                <div class="modal-header bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 text-white rounded-top-5">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="showNewsModalLabel{{ $newsItem->id }}">
                        <i class="fas fa-eye me-2"></i> Chi tiết bài viết
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Tiêu đề và Tác giả trên cùng một dòng -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label fw-bold text-gray-800 text-base">Tiêu đề</label>
                            <div class="form-control bg-white border border-gray-200 rounded-2xl px-5 py-3 text-gray-950 shadow-lg hover:shadow-xl transition-shadow duration-300">{{ $newsItem->title }}</div>
                        </div>
                        <div>
                            <label class="form-label fw-bold text-gray-800 text-base">Tác giả</label>
                            <div class="form-control bg-white border border-gray-200 rounded-2xl px-5 py-3 text-gray-950 shadow-lg hover:shadow-xl transition-shadow duration-300">{{ $newsItem->author }}</div>
                        </div>
                    </div>

                    <!-- Danh mục và Thương hiệu -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label fw-bold text-gray-800 text-base">Danh mục</label>
                            <div class="form-control bg-white border border-gray-200 rounded-2xl px-5 py-3 text-gray-950 shadow-lg hover:shadow-xl transition-shadow duration-300">{{ $newsItem->category->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="form-label fw-bold text-gray-800 text-base">Thương hiệu</label>
                            <div class="form-control bg-white border border-gray-200 rounded-2xl px-5 py-3 text-gray-950 shadow-lg hover:shadow-xl transition-shadow duration-300">{{ $newsItem->brand->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Hình ảnh</label>
                        <div class="text-center">
                            @if($newsItem->image)
                            <img src="{{ asset('storage/' . $newsItem->image) }}"
                                alt="Ảnh bài viết"
                                class="img-fluid rounded-3 shadow-sm"
                                style="max-height: 350px; object-fit: cover; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.15)';"
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                            @else
                            <p class="text-muted fst-italic">Không có hình ảnh</p>
                            @endif
                        </div>
                    </div>

                    <!-- Nội dung chiếm toàn bộ không gian còn lại -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Nội dung</label>
                        <div class="form-control bg-light border-0 shadow-sm rounded-3 px-3 py-2 text-dark" style="white-space: pre-line; height: 200px; overflow-y: auto;">
                            {{ $newsItem->content }}
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold shadow-lg transition-all hover:scale-105" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle me-2"></i> Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit News Modals -->
    <div class="modal fade" id="editNewsModal{{ $newsItem->id }}" tabindex="-1" aria-labelledby="editNewsModalLabel{{ $newsItem->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editNewsModalLabel{{ $newsItem->id }}">
                        <i class="fas fa-edit me-2"></i> Chỉnh sửa bài viết #{{ $newsItem->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('bai-viet.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Hidden slug (có thể sửa thành input nếu cần) --}}
                        <input type="hidden" name="slug" value="{{ old('slug', $newsItem->slug) }}">

                        <div class="mb-3">
                            <label for="title_{{ $newsItem->id }}" class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="title" id="title_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" value="{{ old('title', $newsItem->title) }}" required>
                            @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
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
                            @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
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
                            @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author_{{ $newsItem->id }}" class="form-label fw-semibold">Tác giả</label>
                            <input type="text" name="author" id="author_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" value="{{ old('author', $newsItem->author) }}" required>
                            @error('author') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_{{ $newsItem->id }}" class="form-label fw-semibold">Hình ảnh</label>
                            <input type="file" name="image" id="image_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" accept="image/*">
                            @if($newsItem->image)
                            <img src="{{ asset('storage/' . $newsItem->image) }}" alt="Current image" class="mt-2" style="max-width: 100px;">
                            @endif
                            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content_{{ $newsItem->id }}" class="form-label fw-semibold">Nội dung</label>
                            <textarea name="content" id="content_{{ $newsItem->id }}" class="form-control border-0 shadow-sm" rows="6" required>{{ old('content', $newsItem->content) }}</textarea>
                            @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-select border-0 shadow-sm" required>
                                <option value="1" {{ old('status', $newsItem->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status', $newsItem->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
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