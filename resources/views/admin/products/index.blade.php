@extends('admin.layout')
@section('title', 'Sản phẩm')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Sản Phẩm</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                    </ol>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm rounded-lg mb-4 p-3 border-0 bg-light">
            <div class="card-body">
                <form action="{{ route('san-pham.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-primary">Danh mục</label>
                                        <select name="category_id" class="form-select form-select-sm border-primary">
                                            <option value="">-- Tất cả --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-primary">Thương hiệu</label>
                                        <select name="brand_id" class="form-select form-select-sm border-primary">
                                            <option value="">-- Tất cả --</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-primary">Sắp xếp</label>
                                        <select name="sort_by" class="form-select form-select-sm border-primary">
                                            <option value="">-- Mặc định --</option>
                                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                            <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-primary">Khoảng giá</label>
                                        <div class="d-flex gap-2">
                                            <input type="number" name="min_price" class="form-control form-control-sm border-primary" placeholder="Từ" value="{{ request('min_price') }}">
                                            <input type="number" name="max_price" class="form-control form-control-sm border-primary" placeholder="Đến" value="{{ request('max_price') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('san-pham.index') }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                                    <i class="fas fa-sync"></i> Làm mới
                                </a>
                                <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fas fa-plus"></i> Thêm sản phẩm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="gamingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="gamingModalLabel">Thêm sản phẩm mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('san-pham.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="brand_id" class="form-label">Thương hiệu</label>
                                        <select name="brand_id" id="brand_id" class="form-select">
                                            <option value="">Chọn thương hiệu</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->id }}" {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <span class=" text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Danh mục</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class=" text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">Tên sản phẩm</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                            placeholder="Nhập tên sản phẩm" required>
                                        @error('name')
                                            <span class=" text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Giá</label>
                                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}"
                                            placeholder="Nhập giá sản phẩm" min="0" required>
                                        @error('price')
                                            <span class=" text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="stock" class="form-label">Số lượng ban đầu</label>
                                        <input type="number" name="stock" id="stock" class="form-control"
                                            value="{{ old('stock', 0) }}" placeholder="Nhập số lượng" min="0">
                                        @error('stock')
                                            <span class=" text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea name="description" id="description" class="form-control"
                                            placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <div class="custom-file-upload">
                                            <label for="img" class="form-label">Chọn hình ảnh</label>
                                            <input type="file" name="img" id="img" class="form-control" accept="image/*" style="display: none;">
                                        </div>
                                        <div class="image-preview mt-2" id="preview_add"></div>
                                        @error('img')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Lưu sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ProductTable" class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Hình</th>
                                    <th class="text-center">Tên</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Danh mục</th>
                                    <th class="text-center">Thương hiệu</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td class="text-center">
                                            @if (!empty($item->image_url))
                                                <img src="{{ asset($item->image_url) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $item->name }}">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                        <td class="text-center">{{ $item->total_stock }}</td>
                                        <td>{{ optional($item->category)->name ?? 'Chưa có danh mục' }}</td>
                                        <td>{{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-start gap-2">
                                                <button type="button" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="modal"
                                                    data-bs-target="#showModal{{ $item->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('san-pham.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold" id="showModalLabel{{ $item->id }}">Chi tiết sản phẩm #{{ $item->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-4">
                                                                <div class="col-md-4 d-flex justify-content-center align-items-center">
                                                                    @if (!empty($item->image_url))
                                                                        <img src=" {{ asset($item->image_url) }}" class="img-fluid" alt="{{ $item->name }}">
                                                                    @else
                                                                        <div class="bg-light rounded p-3 text-muted text-center"
                                                                            style="width: 200px; height: 200px; line-height: 200px;">Chưa có ảnh</div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="card border-0 p-3">
                                                                        <p class="mb-2"><strong>Tên:</strong> {{ $item->name }}</p>
                                                                        <p class="mb-2"><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                                                        </p>
                                                                        <p class="mb-2"><strong>Số lượng:</strong> {{ $item->total_stock }}</p>
                                                                        <p class="mb-2"><strong>Danh mục:</strong> {{ optional($item->category)->name ?? 'Chưa có danh mục' }}</p>
                                                                        <p class="mb-2"><strong>Thương hiệu:</strong> {{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</p>
                                                                        <p class="mb-0"><strong>Mô tả:</strong> {{ $item->description ?? 'Chưa có mô tả' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}">Chỉnh sửa sản phẩm #{{ $item->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($errors->any())
                                                                <div class="alert alert-danger">
                                                                    <ul>
                                                                        @foreach ($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            <form action="{{ route('san-pham.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="row g-4">
                                                                    <div class="col-md-6">
                                                                        <label for="brand_id_{{ $item->id }}" class="form-label">Thương hiệu</label>
                                                                        <select name="brand_id" id="brand_id_{{ $item->id }}" class="form-select">
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
                                                                        <label for="category_id_{{ $item->id }}" class="form-label">Danh mục</label>
                                                                        <select name="category_id" id="category_id_{{ $item->id }}" class="form-select">
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
                                                                        <label for="name_{{ $item->id }}" class="form-label">Tên sản phẩm</label>
                                                                        <input type="text" name="name" id="name_{{ $item->id }}" class="form-control"
                                                                            value="{{ old('name', $item->name) }}" placeholder="Nhập tên sản phẩm" required>
                                                                        @error('name')
                                                                            <span class="text-danger small">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="price_{{ $item->id }}" class="form-label">Giá</label>
                                                                        <input type="number" name="price" id="price_{{ $item->id }}" class="form-control"
                                                                            value="{{ old('price', $item->price) }}" placeholder="Nhập giá sản phẩm" min="0"
                                                                            required>
                                                                        @error('price')
                                                                            <span class="text-danger small">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="stock_{{ $item->id }}" class="form-label">Số lượng tồn kho</label>
                                                                        <input type="number" name="stock" id="stock_{{ $item->id }}" class="form-control"
                                                                            value="{{ old('stock', $item->total_stock) }}" placeholder="Nhập số lượng tồn kho"
                                                                            min="0" required>
                                                                        @error('stock')
                                                                            <span class="text-danger small">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description_{{ $item->id }}" class="form-label">Mô tả</label>
                                                                        <textarea name="description" id="description_{{ $item->id }}" class="form-control"
                                                                            placeholder="Nhập mô tả sản phẩm"
                                                                            rows="3">{{ old('description', $item->description) }}</textarea>
                                                                        @error('description')
                                                                            <span class="text-danger small">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="custom-file-upload">
                                                                            <label for="img_{{ $item->id }}" class="form-label">Chọn hình ảnh</label>
                                                                            <input type="file" name="img" id="img_{{ $item->id }}" class="form-control"
                                                                                accept="image/*">
                                                                        </div>
                                                                        <div class="image-preview mt-2" id="preview_{{ $item->id }}">
                                                                            @if ($item->image_url)
                                                                                <img src="{{ asset($item->image_url) }}" class="img-thumbnail" alt="{{ $item->name }}"
                                                                                    style="max-width: 200px;">
                                                                            @endif
                                                                        </div>
                                                                        @error('img')
                                                                            <span class="text-danger small">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection