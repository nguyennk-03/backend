@extends('admin.layout')
@section('title', 'Danh sách sản phẩm')
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
                        <!-- Bộ lọc chính -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary">Danh mục</label>
                                    <select name="category_id" class="form-select border-primary">
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
                                    <select name="brand_id" class="form-select border-primary">
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
                                    <select name="sort_by" class="form-select border-primary">
                                        <option value="">-- Mặc định --</option>
                                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>
                                            Giá tăng dần</option>
                                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>
                                            Giá giảm dần</option>
                                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất
                                        </option>
                                        <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary">Khoảng giá</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" name="min_price" class="form-control border-primary"
                                            placeholder="Từ" value="{{ request('min_price') }}">
                                        <input type="number" name="max_price" class="form-control border-primary"
                                            placeholder="Đến" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Nút tìm kiếm, làm mới, thêm sản phẩm (nhỏ hơn) -->
                        <div class="col-md-4 d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm ">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('san-pham.index') }}" class="btn btn-warning fw-bold shadow-sm">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                            <button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addProductModal">
                                <i class="fas fa-plus"></i> Thêm sản phẩm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="gamingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gamingModalLabel">Thêm sản phẩm mới</h5>
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
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
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
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
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Nhập tên sản phẩm" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" name="price" id="price" class="form-control"
                                        value="{{ old('price') }}" placeholder="Nhập giá sản phẩm" min="0" required>
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Số lượng ban đầu</label>
                                    <input type="number" name="stock" id="stock" class="form-control"
                                        value="{{ old('stock', 0) }}" placeholder="Nhập số lượng" min="0">
                                    @error('stock')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control"
                                        placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="img" class="form-label">Hình ảnh</label>
                                    <input type="file" name="img" id="img" class="form-control" accept="image/*">
                                    @error('img')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
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
                                <th>ID</th>
                                <th>Hình</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Danh mục</th>
                                <th>Thương hiệu</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if (!empty($item->image_url))
                                            <img src="{{ asset($item->image_url) }}" class="img-thumbnail" width="100"
                                                alt="{{ $item->name }}">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ $item->total_stock }}</td>
                                    <td>{{ optional($item->category)->name ?? 'Chưa có danh mục' }}</td>
                                    <td>{{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#showModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('san-pham.destroy', $item->id) }}" method="POST"
                                                class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="showModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="showModalLabel{{ $item->id }}">Chi tiết sản
                                                            phẩm
                                                            #{{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                @if (!empty($item->image_url))
                                                                    <img src="{{ asset($item->image_url) }}"
                                                                        class="img-fluid rounded" alt="{{ $item->name }}">
                                                                @else
                                                                    <span class="text-muted">Chưa có ảnh</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p><strong>Tên:</strong> {{ $item->name }}</p>
                                                                <p><strong>Giá:</strong>
                                                                    {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                                                                <p><strong>Số lượng:</strong> {{ $item->total_stock }}</p>
                                                                <p><strong>Danh mục:</strong>
                                                                    {{ optional($item->category)->name ?? 'Chưa có danh mục' }}
                                                                </p>
                                                                <p><strong>Thương hiệu:</strong>
                                                                    {{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}
                                                                </p>
                                                                <p><strong>Mô tả:</strong>
                                                                    {{ $item->description ?? 'Chưa có mô tả' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Chỉnh sửa sản
                                                            phẩm #{{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('san-pham.update', $item->id) }}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
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
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6 mb-3">
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
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <label for="name_{{ $item->id }}" class="form-label">Tên sản
                                                                        phẩm</label>
                                                                    <input type="text" name="name" id="name_{{ $item->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('name', $item->name) }}"
                                                                        placeholder="Nhập tên sản phẩm" required>
                                                                    @error('name')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label for="price_{{ $item->id }}"
                                                                        class="form-label">Giá</label>
                                                                    <input type="number" name="price" id="price_{{ $item->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('price', $item->price) }}"
                                                                        placeholder="Nhập giá sản phẩm" min="0" required>
                                                                    @error('price')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label for="stock_{{ $item->id }}" class="form-label">Số
                                                                        lượng
                                                                        tồn kho</label>
                                                                    <input type="number" name="stock" id="stock_{{ $item->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('stock', $item->total_stock) }}"
                                                                        placeholder="Nhập số lượng tồn kho" min="0">
                                                                    @error('stock')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <label for="description_{{ $item->id }}"
                                                                        class="form-label">Mô
                                                                        tả</label>
                                                                    <textarea name="description"
                                                                        id="description_{{ $item->id }}" class="form-control"
                                                                        placeholder="Nhập mô tả sản phẩm">{{ old('description', $item->description) }}</textarea>
                                                                    @error('description')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <label for="img_{{ $item->id }}" class="form-label">Hình
                                                                        ảnh</label>
                                                                    <input type="file" name="img" id="img_{{ $item->id }}"
                                                                        class="form-control" accept="image/*">
                                                                    @if ($item->image_url)
                                                                        <img src="{{ asset($item->image_url) }}"
                                                                            class="img-thumbnail mt-2" width="100"
                                                                            alt="{{ $item->name }}">
                                                                    @endif
                                                                    @error('img')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
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