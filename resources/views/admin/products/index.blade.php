@extends('admin.layout')
@section('titlepage', 'Danh sách sản phẩm')
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm rounded">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Danh Sách Sản Phẩm</h4>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">
                        <i class="fas fa-plus fa-sm"></i> Thêm Sản Phẩm
                    </button>
                </div>

                <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thêm Sản Phẩm</h5>
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
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" name="quantity" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-image fa-sm"></i> Hình ảnh</label>
                                        <input type="file" name="images" class="form-control" multiple>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary btn-sm ms-2">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="ProductTable" class="table table-striped table-hover">
                        <thead class="bg-dark text-white">
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
                                        @if (isset($item->images[0]))
                                            <img src="{{ asset('storage/' . $item->images[0]->image_path) }}"
                                                class="img-thumbnail" width="50">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ $item->quantity ?? '0' }}</td>
                                    <td>{{ optional($item->category)->name ?? 'Chưa có danh mục' }}</td>
                                    <td>{{ optional($item->brand)->name ?? 'Chưa có thương hiệu' }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('productEdit', $item->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="{{ route('productDelete', $item->id) }}" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
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
