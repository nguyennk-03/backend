@extends('admin.layout')
@section('titlepage', 'Danh sách sản phẩm')
@section('content')

    <div class="container-fluid">

        <!-- Start Page Title -->
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
        <!-- End Page Title -->

        <!-- Hiển thị thông báo -->
        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Danh sách sản phẩm -->
        <div class="card shadow-lg rounded-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Danh Sách Sản Phẩm</h4>

                    <div class="d-flex gap-2">
                        <!-- Form tìm kiếm -->
                        <form action="{{ route('products') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Tìm kiếm..."
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </form>

                        <!-- Nút thêm sản phẩm -->
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal"
                            data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </button>
                    </div>
                </div>

                <!-- Modal thêm sản phẩm -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="addProductLabel">Thêm Sản Phẩm</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

                                <form action="{{ route('productAdd') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Danh mục</label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Chọn danh mục</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thương hiệu</label>
                                        <select name="brand_id" class="form-select" required>
                                            <option value="">Chọn thương hiệu</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tên sản phẩm</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Giá</label>
                                        <input type="number" name="price" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Số lượng tồn</label>
                                        <input type="number" name="stock" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ảnh sản phẩm</label>
                                        <input type="file" name="img" class="form-control">
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary ms-2">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bảng danh sách sản phẩm -->
                <table id="product-table" class="table table-striped table-hover">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><img src="{{ asset($item->img) }}" alt="Ảnh" width="50"></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $item->stock ?? '0' }}</td>
                                <td>{{ $item->category ? $item->category->name : 'Chưa có danh mục' }}</td>
                                <td>{{ $item->brand ? $item->brand->name : 'Chưa có thương hiệu' }}</td>
                                <td class="action-icons">
                                    <a href="#" class="btn btn-warning btn-sm edit-btn" data-id="{{ $item->id }}">Sửa</a>
                                    <a href="{{ route('productDelete', $item->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>



                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center bg-dark text-white p-2 rounded">
                    <span>Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng
                        {{ $products->total() }} sản phẩm</span>
                    <div>{{ $products->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection