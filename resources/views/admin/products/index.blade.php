<!-- resources/views/admin/products/index.blade.php -->
@extends('admin.layout')
@section('title', 'Sản phẩm')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-box-open me-2"></i>Quản Lý Sản Phẩm</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="card shadow-sm rounded-lg">
            <div class="card-body p-4">
                <form action="{{ route('san-pham.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-list-ul me-1"></i> Danh mục
                            </label>
                            <select name="category_id" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag me-1"></i> Thương hiệu
                            </label>
                            <select name="brand_id" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-sort me-1"></i> Sắp xếp
                            </label>
                            <select name="sort_by" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Mặc định --</option>
                                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-money-bill-wave me-1"></i> Khoảng giá
                            </label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" class="form-control form-control-sm border-0 shadow-sm" placeholder="Từ" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="form-control form-control-sm border-0 shadow-sm" placeholder="Đến" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('san-pham.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                        <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addProductModalLabel"><i class="fas fa-plus-circle me-2"></i> Thêm sản phẩm mới</h5>
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
                    <form action="{{ route('san-pham.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="brand_id" class="form-label fw-semibold">Thương hiệu</label>
                                <select name="brand_id" id="brand_id" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn thương hiệu</option>
                                    @foreach ($brands as $item)
                                    <option value="{{ $item->id }}" {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                                <select name="category_id" id="category_id" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">Tên sản phẩm</label>
                                <input type="text" name="name" id="name" class="form-control border-0 shadow-sm" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm" required>
                                @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" id="description" class="form-control border-0 shadow-sm" placeholder="Nhập mô tả sản phẩm" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <h5 class="fw-semibold">Biến thể</h5>
                                <div id="variants">
                                    <div class="variant mb-3 border p-3 rounded">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Kích thước</label>
                                                <select name="variants[0][size_id]" class="form-select border-0 shadow-sm" required>
                                                    <option value="">Chọn kích thước</option>
                                                    @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}" {{ old('variants.0.size_id') == $size->id ? 'selected' : '' }}>
                                                        {{ $size->size ?? 'N/A' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('variants.0.size_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Màu sắc</label>
                                                <select name="variants[0][color_id]" class="form-select border-0 shadow-sm" required>
                                                    <option value="">Chọn màu sắc</option>
                                                    @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}" {{ old('variants.0.color_id') == $color->id ? 'selected' : '' }}>
                                                        {{ $color->color_name ?? 'N/A' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('variants.0.color_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Giá</label>
                                                <input type="number" name="variants[0][price]" class="form-control border-0 shadow-sm" value="{{ old('variants.0.price') }}" placeholder="Nhập giá" min="0" step="0.01" required>
                                                @error('variants.0.price')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Phần trăm giảm giá</label>
                                                <input type="number" name="variants[0][discount_percent]" class="form-control border-0 shadow-sm" value="{{ old('variants.0.discount_percent', 0) }}" placeholder="Nhập phần trăm giảm" min="0" max="100">
                                                @error('variants.0.discount_percent')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Số lượng</label>
                                                <input type="number" name="variants[0][stock_quantity]" class="form-control border-0 shadow-sm" value="{{ old('variants.0.stock_quantity', 0) }}" placeholder="Nhập số lượng" min="0" required>
                                                @error('variants.0.stock_quantity')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Hình ảnh</label>
                                                <input type="file" name="variants[0][images][]" class="form-control border-0 shadow-sm variant-image-input" accept="image/*" multiple data-preview="preview_variants_0">
                                                <div class="image-preview mt-2 d-flex flex-wrap gap-2" id="preview_variants_0"></div>
                                                @error('variants.0.images.*')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa biến thể</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-variant" class="btn btn-secondary btn-sm mt-2">Thêm biến thể</button>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu sản phẩm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="ProductTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Hình</th>
                            <th class="text-center py-3">Tên</th>
                            <th class="text-center py-3">Giá</th>
                            <th class="text-center py-3">Số lượng</th>
                            <th class="text-center py-3">Danh mục</th>
                            <th class="text-center py-3">Thương hiệu</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center">
                                @php
                                $mainImage = $item->variants->first()?->images->firstWhere('is_main', true);
                                @endphp
                                @if ($mainImage)
                                <img src="{{ asset($mainImage->path) }}" class="img-thumbnail rounded" style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $item->name }}">
                                @else
                                <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">
                                {{ number_format($item->variants->max('discounted_price') ?? 0, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="text-center">{{ $item->variants->sum('stock_quantity') }}</td>
                            <td>{{ optional($item->category)->name ?? 'Chưa có danh mục' }}</td>
                            <td>{{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('san-pham.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không có sản phẩm nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Show Product Modal -->
    @foreach ($products as $item)
    <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="showModalLabel{{ $item->id }}"><i class="fas fa-info-circle me-2"></i> Chi tiết sản phẩm #{{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                            @php
                            $mainImage = $item->variants->first()?->images->firstWhere('is_main', true);
                            @endphp

                            @if ($mainImage)
                            <img src="{{ asset($mainImage->path) }}" class="img-fluid rounded shadow-sm" alt="{{ $item->name }}">
                            @else
                            <div class="bg-light rounded p-3 text-muted text-center" style="width: 200px; height: 200px; line-height: 200px;">
                                Chưa có ảnh
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 p-3 rounded shadow-sm">
                                <p class="mb-2"><strong>Tên:</strong> {{ $item->name }}</p>
                                <p class="mb-2"><strong>Danh mục:</strong> {{ optional($item->category)->name ?? 'Chưa có danh mục' }}</p>
                                <p class="mb-2"><strong>Thương hiệu:</strong> {{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</p>
                                <p class="mb-2"><strong>Mô tả:</strong> {{ $item->description ?? 'Chưa có mô tả' }}</p>
                                <p class="mb-2"><strong>Biến thể:</strong></p>
                                <ul>
                                    @foreach ($item->variants as $variant)
                                    <li>
                                        Kích thước: {{ $variant->size->size ?? 'N/A' }},
                                        Màu: {{ $variant->color->color_name ?? 'N/A' }},
                                        Giá: {{ number_format($variant->discounted_price, 0, ',', '.') }} VNĐ,
                                        Số lượng: {{ $variant->stock_quantity }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}"><i class="fas fa-edit me-2"></i> Chỉnh sửa sản phẩm #{{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('san-pham.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="brand_id_{{ $item->id }}" class="form-label fw-semibold">Thương hiệu</label>
                                <select name="brand_id" id="brand_id_{{ $item->id }}" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $item->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category_id_{{ $item->id }}" class="form-label fw-semibold">Danh mục</label>
                                <select name="category_id" id="category_id_{{ $item->id }}" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="name_{{ $item->id }}" class="form-label fw-semibold">Tên sản phẩm</label>
                                <input type="text" name="name" id="name_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('name', $item->name) }}" placeholder="Nhập tên sản phẩm" required>
                                @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description_{{ $item->id }}" class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" id="description_{{ $item->id }}" class="form-control border-0 shadow-sm" placeholder="Nhập mô tả sản phẩm" rows="4">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <h5 class="fw-semibold">Biến thể</h5>
                                <div id="variants_{{ $item->id }}">
                                    @foreach ($item->variants as $index => $variant)
                                    <div class="variant mb-3 border p-3 rounded">
                                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Kích thước</label>
                                                <select name="variants[{{ $index }}][size_id]" class="form-select border-0 shadow-sm" required>
                                                    <option value="">Chọn kích thước</option>
                                                    @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}" {{ old('variants.' . $index . '.size_id', $variant->size_id) == $size->id ? 'selected' : '' }}>
                                                        {{ $size->size ?? 'N/A' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('variants.' . $index . '.size_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Màu sắc</label>
                                                <select name="variants[{{ $index }}][color_id]" class="form-select border-0 shadow-sm" required>
                                                    <option value="">Chọn màu sắc</option>
                                                    @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}" {{ old('variants.' . $index . '.color_id', $variant->color_id) == $color->id ? 'selected' : '' }}>
                                                        {{ $color->color_name ?? 'N/A' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('variants.' . $index . '.color_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Giá</label>
                                                <input type="number" name="variants[{{ $index }}][price]" class="form-control border-0 shadow-sm" value="{{ old('variants.' . $index . '.price', $variant->price) }}" placeholder="Nhập giá" min="0" step="0.01" required>
                                                @error('variants.' . $index . '.price')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Phần trăm giảm giá</label>
                                                <input type="number" name="variants[{{ $index }}][discount_percent]" class="form-control border-0 shadow-sm" value="{{ old('variants.' . $index . '.discount_percent', $variant->discount_percent) }}" placeholder="Nhập phần trăm giảm" min="0" max="100">
                                                @error('variants.' . $index . '.discount_percent')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Số lượng</label>
                                                <input type="number" name="variants[{{ $index }}][stock_quantity]" class="form-control border-0 shadow-sm" value="{{ old('variants.' . $index . '.stock_quantity', $variant->stock_quantity) }}" placeholder="Nhập số lượng" min="0" required>
                                                @error('variants.' . $index . '.stock_quantity')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Hình ảnh hiện tại</label>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @foreach ($variant->images as $image)
                                                    <img src="{{ asset($image->path) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" alt="Variant Image">
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Hình ảnh mới</label>
                                                <input type="file" name="variants[{{ $index }}][images][]" class="form-control border-0 shadow-sm variant-image-input" accept="image/*" multiple data-preview="preview_variants_{{ $item->id }}_{{ $index }}">
                                                <div class="image-preview mt-2 d-flex flex-wrap gap-2" id="preview_variants_{{ $item->id }}_{{ $index }}"></div>
                                                @error('variants.' . $index . '.images.*')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa biến thể</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2 add-variant" data-product-id="{{ $item->id }}">Thêm biến thể</button>
                            </div>
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