@extends('admin.layout')
@section('titlepage', 'Danh sách sản phẩm')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Danh sách sản phẩm</h4>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Danh Sách Sản Phẩm</h4>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                    </button>
                </div>
            </div>

            <!-- Modal thêm sản phẩm -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductLabel">Thêm Sản Phẩm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('productAdd') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Hình ảnh</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary ms-2">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng danh sách sản phẩm -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Hình ảnh</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                        <td>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="50">
                        </td>
                        <td>
                            <a href="{{ route('productEdit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('productDelete', $product->id) }}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Hiển thị <strong>{{ $products->firstItem() }}</strong> đến <strong>{{ $products->lastItem() }}</strong> trong tổng số
                    <strong>{{ $products->total() }}</strong> sản phẩm
                </div>
                <div class="pagination-container">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
