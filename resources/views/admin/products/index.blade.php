@extends('admin.layout')
@section('title', 'Sản phẩm')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="fas fa-box-open me-2"></i> Sản Phẩm</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Thông báo thành công -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Bộ lọc và nút hành động -->
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

    <!-- Modal thêm sản phẩm -->
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
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold">Giá</label>
                                <input type="number" name="variants[0][price]" id="price" class="form-control border-0 shadow-sm" value="{{ old('variants.0.price') }}" placeholder="Nhập giá sản phẩm" min="0" step="0.01" required>
                                @error('variants.0.price')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label fw-semibold">Số lượng ban đầu</label>
                                <input type="number" name="variants[0][stock_quantity]" id="stock" class="form-control border-0 shadow-sm" value="{{ old('variants.0.stock_quantity', 0) }}" placeholder="Nhập số lượng" min="0">
                                @error('variants.0.stock_quantity')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="size_id" class="form-label fw-semibold">Kích thước</label>
                                <select name="variants[0][size_id]" id="size_id" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn kích thước</option>
                                    @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}" {{ old('variants.0.size_id') == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('variants.0.size_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="color_id" class="form-label fw-semibold">Màu sắc</label>
                                <select name="variants[0][color_id]" id="color_id" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn màu sắc</option>
                                    @foreach ($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('variants.0.color_id') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('variants.0.color_id')
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
                                <label for="image" class="form-label fw-semibold">Chọn hình ảnh</label>
                                <div class="input-group">
                                    <input type="file" name="images[]" id="image" class="form-control border-0 shadow-sm" accept="image/*" data-preview="preview_add" multiple>
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image').click()">Chọn file</button>
                                </div>
                                <div class="image-preview mt-3" id="preview_add">
                                    <img id="preview_add_img" src="" alt="Ảnh xem trước" class="rounded shadow-sm d-none" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                @error('images.*')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
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

    <!-- Bảng sản phẩm -->
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
                                @if (!empty($item->image))
                                <img src="{{ asset($item->image) }}" class="img-thumbnail rounded" style="object-fit: cover; max-width: 100px; max-height: 100px;" alt="{{ $item->name }}">
                                @else
                                <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">{{ number_format($item->variants->max('price') ?? 0, 0, ',', '.') }} VNĐ</td>
                            <td class="text-center">{{ $item->total_stock }}</td>
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

    <!-- Modal xem chi tiết -->
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
                            @if (!empty($item->image))
                            <img src="{{ asset($item->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $item->name }}">
                            @else
                            <div class="bg-light rounded p-3 text-muted text-center" style="width: 200px; height: 200px; line-height: 200px;">
                                Chưa có ảnh
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 p-3 rounded shadow-sm">
                                <p class="mb-2"><strong>Tên:</strong> {{ $item->name }}</p>
                                <p class="mb-2"><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                                <p class="mb-2"><strong>Số lượng:</strong> {{ $item->total_stock }}</p>
                                <p class="mb-2"><strong>Danh mục:</strong> {{ optional($item->category)->name ?? 'Chưa có danh mục' }}</p>
                                <p class="mb-2"><strong>Thương hiệu:</strong> {{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</p>
                                <p class="mb-0"><strong>Mô tả:</strong> {{ $item->description ?? 'Chưa có mô tả' }}</p>
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

    <!-- Modal chỉnh sửa -->
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
                            <div class="col-md-6">
                                <label for="price_{{ $item->id }}" class="form-label fw-semibold">Giá</label>
                                <input type="number" name="variants[0][price]" id="price_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('variants.0.price', $item->variants()->first()->price ?? 0) }}" placeholder="Nhập giá sản phẩm" min="0" step="0.01" required>
                                @error('variants.0.price')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stock_{{ $item->id }}" class="form-label fw-semibold">Số lượng tồn kho</label>
                                <input type="number" name="variants[0][stock_quantity]" id="stock_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('variants.0.stock_quantity', $item->total_stock) }}" placeholder="Nhập số lượng tồn kho" min="0" required>
                                @error('variants.0.stock_quantity')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="size_id_{{ $item->id }}" class="form-label fw-semibold">Kích thước</label>
                                <select name="variants[0][size_id]" id="size_id_{{ $item->id }}" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn kích thước</option>
                                    @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}" {{ old('variants.0.size_id', $item->variants()->first()->size_id ?? '') == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('variants.0.size_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="color_id_{{ $item->id }}" class="form-label fw-semibold">Màu sắc</label>
                                <select name="variants[0][color_id]" id="color_id_{{ $item->id }}" class="form-select border-0 shadow-sm">
                                    <option value="">Chọn màu sắc</option>
                                    @foreach ($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('variants.0.color_id', $item->variants()->first()->color_id ?? '') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('variants.0.color_id')
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
                                <label for="image_{{ $item->id }}" class="form-label fw-semibold">Chọn hình ảnh</label>
                                <div class="input-group">
                                    <input type="file" name="images[]" id="image_{{ $item->id }}" class="form-control border-0 shadow-sm" accept="image/*" data-preview="preview_{{ $item->id }}" multiple>
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image_{{ $item->id }}').click()">Chọn file</button>
                                </div>
                                <div class="mt-3 d-flex align-items-center gap-3">
                                    @if (!empty($item->image))
                                    <div class="current-image">
                                        <label class="form-label small text-muted">Hình ảnh hiện tại:</label>
                                        <img src="{{ asset($item->image) }}" alt="Hình ảnh hiện tại" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                    @endif
                                    <div class="preview-image">
                                        <label class="form-label small text-muted">Hình ảnh xem trước:</label>
                                        <img id="preview_{{ $item->id }}" src="" alt="Ảnh xem trước" class="rounded shadow-sm d-none" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                </div>
                                @error('images.*')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
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

@section('scripts')
<script>
    // Xem trước ảnh khi chọn file
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            const previewImg = document.getElementById(`${previewId}_img`) || document.getElementById(previewId);
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewImg.src = '';
                previewImg.classList.add('d-none');
            }
        });
    });
</script>
@endsection