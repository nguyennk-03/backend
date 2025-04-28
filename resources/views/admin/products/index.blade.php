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

    <!-- Error Message -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="card shadow-sm rounded-lg">
            <div class="card-body p-4">
                <form action="{{ route('san-pham.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-sort me-1"></i> Sắp xếp</label>
                            <select name="sort_by" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Mặc định --</option>
                                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-money-bill-wave me-1"></i> Khoảng giá</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" class="form-control form-control-sm border-0 shadow-sm" placeholder="Từ" value="{{ request('min_price') }}" min="0">
                                <input type="number" name="max_price" class="form-control form-control-sm border-0 shadow-sm" placeholder="Đến" value="{{ request('max_price') }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-tags me-1"></i> Giảm giá</label>
                            <select name="sale" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('sale') == '1' ? 'selected' : '' }}>Đang giảm giá</option>
                                <option value="0" {{ request('sale') == '0' ? 'selected' : '' }}>Không giảm giá</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-fire me-1"></i> Nổi bật</label>
                            <select name="hot" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="0" {{ request('hot') == '0' ? 'selected' : '' }}>Thường</option>
                                <option value="1" {{ request('hot') == '1' ? 'selected' : '' }}>Mới</option>
                                <option value="2" {{ request('hot') == '2' ? 'selected' : '' }}>Nổi bật</option>
                                <option value="3" {{ request('hot') == '3' ? 'selected' : '' }}>Bán chạy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-eye me-1"></i> Trạng thái</label>
                            <select name="status" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('san-pham.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm" onclick="resetForm(this)">
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
                    <h5 class="modal-title fw-bold" id="addProductModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Thêm sản phẩm mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="{{ route('san-pham.store') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
                        @csrf
                        <div class="row g-4">
                            {{-- Thương hiệu --}}
                            <div class="col-md-6">
                                <label for="brand_id" class="form-label fw-semibold">Thương hiệu</label>
                                <select name="brand_id" id="brand_id" class="form-select border-0 shadow-sm" required>
                                    <option value="">Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Danh mục --}}
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                                <select name="category_id" id="category_id" class="form-select border-0 shadow-sm" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tên sản phẩm --}}
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">Tên sản phẩm</label>
                                <input type="text" name="name" id="name" class="form-control border-0 shadow-sm" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Giá và Số lượng --}}
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-semibold">Giá</label>
                                <input type="number" name="price" id="price" class="form-control border-0 shadow-sm" value="{{ old('price') }}" placeholder="Nhập giá" min="0" step="0.01" required>
                                @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label fw-semibold">Số lượng</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control border-0 shadow-sm" value="{{ old('stock_quantity', 0) }}" placeholder="Nhập số lượng" min="0" required>
                                @error('stock_quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Hình ảnh sản phẩm --}}
                            <div class="col-md-6">
                                <label for="image" class="form-label fw-semibold">Hình ảnh</label>
                                <input type="file" name="image" id="image" class="form-control border-0 shadow-sm" accept="image/jpeg,image/png,image/jpg">
                                <div id="preview_product" class="image-preview mt-2 d-flex flex-wrap gap-2"></div>
                                @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Mô tả sản phẩm --}}
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" id="description" class="form-control border-0 shadow-sm" rows="4" placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Sale, Hot, Status --}}
                            <div class="col-md-4">
                                <label for="sale" class="form-label fw-semibold">Giảm giá</label>
                                <select name="sale" id="sale" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('sale', 0) == 0 ? 'selected' : '' }}>Không giảm giá</option>
                                    <option value="1" {{ old('sale') == 1 ? 'selected' : '' }}>Đang giảm giá</option>
                                </select>
                                @error('sale') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hot" class="form-label fw-semibold">Nổi bật</label>
                                <select name="hot" id="hot" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('hot', 0) == 0 ? 'selected' : '' }}>Thường</option>
                                    <option value="1" {{ old('hot') == 1 ? 'selected' : '' }}>Mới</option>
                                    <option value="2" {{ old('hot') == 2 ? 'selected' : '' }}>Nổi bật</option>
                                    <option value="3" {{ old('hot') == 3 ? 'selected' : '' }}>Bán chạy</option>
                                </select>
                                @error('hot') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" id="status" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Size và Color --}}
                            <div class="col-md-6">
                                <label for="size" class="form-label fw-semibold">Kích thước</label>
                                <select name="size" id="size" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn kích thước --</option>
                                    @foreach ($sizes as $size)
                                    <option value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>{{ $size ->name }}</option>
                                    @endforeach
                                </select>
                                @error('size') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="color" class="form-label fw-semibold">Màu sắc</label>
                                <select name="color" id="color" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn màu sắc --</option>
                                    @foreach ($colors as $color)
                                    <option value="{{ $color }}" {{ old('color') == $color ? 'selected' : '' }}>{{ $color ->name  }}</option>
                                    @endforeach
                                </select>
                                @error('color') <span class="text-danger small">{{ $message }}</span> @enderror
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
                            <th class="text-center py-2">ID</th>
                            <th class="text-center py-2">Hình</th>
                            <th class="text-center py-2">Tên</th>
                            <th class="text-center py-2">Giá</th>
                            <th class="text-center py-2">Số lượng</th>
                            <th class="text-center py-2">Đã bán</th>
                            <th class="text-center py-2">Màu sắc</th>
                            <th class="text-center py-2">Kích thước</th>
                            <th class="text-center py-2">Danh mục</th>
                            <th class="text-center py-2">Thương hiệu</th>
                            <th class="text-center py-2">Trạng thái</th>
                            <th class="text-center py-2">Giảm giá</th>
                            <th class="text-center py-2">Nổi bật</th>
                            <th class="text-center py-2">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center">
                                @if (!empty($item->image) && file_exists(storage_path('app/public/' . $item->image)))
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="img-fluid rounded shadow-sm"
                                    alt="{{ $item->name }}"
                                    style="max-width: 100px; max-height: 100px;">
                                @else
                                <div class="bg-light rounded p-3 text-muted text-center"
                                    style="width: 100px; height: 100px; line-height: 80px;">
                                    Chưa có ảnh
                                </div>
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.')}}₫</td>
                            <td class="text-center">{{ $item->stock_quantity }}</td>
                            <td class="text-center">{{ $item->sold ?? 0 }}</td>
                            <td class="text-center">{{ optional($item->color)->name ?? 'Chưa có màu' }}</td>
                            <td class="text-center">{{ optional($item->size)->name ?? 'Chưa có size' }}</td>
                            <td class="text-center">{{ optional($item->category)->name ?? 'Chưa có danh mục' }}</td>
                            <td class="text-center">{{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item->sale ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $item->sale ? 'Đang giảm giá' : 'Không giảm giá' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge 
                                    {{ $item->hot == 0 ? 'bg-secondary' : 
                                       ($item->hot == 1 ? 'bg-primary' : 
                                       ($item->hot == 2 ? 'bg-warning' : 'bg-success')) }}">
                                    {{ $item->hot == 0 ? 'Thường' : 
                                       ($item->hot == 1 ? 'Mới' : 
                                       ($item->hot == 2 ? 'Nổi bật' : 'Bán chạy') ) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('san-pham.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted py-4">
                                Không có sản phẩm nào để hiển thị.
                            </td>
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
                            @if (!empty($item->image))
                            <img src="{{ asset($item->image) }}"
                                class="img-fluid rounded shadow-sm" alt="{{ $item->name }}">
                            @else
                            <div class="bg-light rounded p-3 text-muted text-center"
                                style="width: 200px; height: 200px; line-height: 200px;">
                                Chưa có ảnh
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 p-3 rounded shadow-sm">
                                <p class="mb-2"><strong>Tên:</strong> {{ $item->name }}</p>
                                <p class="mb-2"><strong>Giá:</strong> {{ $item->price }}</p>
                                <p class="mb-2"><strong>Màu sắc:</strong> {{ $item->color }}</p>
                                <p class="mb-2"><strong>Kích thước:</strong> {{ $item->size }}</p>
                                <p class="mb-2"><strong>Danh mục:</strong> {{ optional($item->category)->name ?? 'Chưa có danh mục' }}</p>
                                <p class="mb-2"><strong>Thương hiệu:</strong> {{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</p>
                                <p class="mb-2"><strong>Trạng thái:</strong> {{ $item->status ? 'Hiển thị' : 'Ẩn' }}</p>
                                <p class="mb-2"><strong>Giảm giá:</strong> {{ $item->sale ? 'Đang giảm giá' : 'Không giảm giá' }}</p>
                                <p class="mb-2"><strong>Nổi bật:</strong> {{ $item->hot == 0 ? 'Thường' : ($item->hot == 1 ? 'Mới' : ($item->hot == 2 ? 'Nổi bật' : 'Bán chạy')) }}</p>
                                <p class="mb-2"><strong>Tổng số lượng:</strong> {{ $item->stock_quantity }}</p>
                                <p class="mb-2"><strong>Đã bán:</strong> {{ $item->sold }}</p>
                                <p class="mb-2"><strong>Mô tả:</strong> {{ $item->description ?? 'Chưa có mô tả' }}</p>
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
                    <form action="{{ route('san-pham.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm{{ $item->id }}">
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
                                <input type="number" name="price" id="price_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('price', $item->price) }}" placeholder="Nhập giá" min="0" step="0.01" required>
                                @error('price')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stock_quantity_{{ $item->id }}" class="form-label fw-semibold">Số lượng</label>
                                <input type="number" name="stock_quantity" id="stock_quantity_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('stock_quantity', $item->stock_quantity) }}" placeholder="Nhập số lượng" min="0" required>
                                @error('stock_quantity')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="image_{{ $item->id }}" class="form-label fw-semibold">Hình ảnh</label>
                                <input type="file" name="image" id="image_{{ $item->id }}" class="form-control border-0 shadow-sm" accept="image/jpeg,image/png,image/jpg" data-preview="preview_product_{{ $item->id }}">
                                @if ($item->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Current Image">
                                </div>
                                @endif
                                <div class="image-preview mt-2 d-flex flex-wrap gap-2" id="preview_product_{{ $item->id }}"></div>
                                @error('image')
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
                            <div class="col-md-4">
                                <label for="sale_{{ $item->id }}" class="form-label fw-semibold">Giảm giá</label>
                                <select name="sale" id="sale_{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('sale', $item->sale) == 0 ? 'selected' : '' }}>Không giảm giá</option>
                                    <option value="1" {{ old('sale', $item->sale) == 1 ? 'selected' : '' }}>Đang giảm giá</option>
                                </select>
                                @error('sale')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="hot_{{ $item->id }}" class="form-label fw-semibold">Nổi bật</label>
                                <select name="hot" id="hot_{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('hot', $item->hot) == 0 ? 'selected' : '' }}>Thường</option>
                                    <option value="1" {{ old('hot', $item->hot) == 1 ? 'selected' : '' }}>Mới</option>
                                    <option value="2" {{ old('hot', $item->hot) == 2 ? 'selected' : '' }}>Nổi bật</option>
                                    <option value="3" {{ old('hot', $item->hot) == 3 ? 'selected' : '' }}>Bán chạy</option>
                                </select>
                                @error('hot')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status_{{ $item->id }}" class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" id="status_{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('status', $item->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status', $item->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
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