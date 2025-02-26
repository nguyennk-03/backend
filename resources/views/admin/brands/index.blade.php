@extends('admin.layout')
@section('titlepage', 'Danh sách thương hiệu')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Thương hiệu</li>
                    </ol>
                </div>
                <h4 class="page-title">Thương hiệu sản phẩm</h4>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="header-title font-weight-bold mb-0">Danh Sách Thương Hiệu</h4>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('brands.index') }}" method="GET" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Tìm</button>
                            </div>
                        </form>
                        <button type="button" class="btn btn-success ms-3" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                            <i class="bi bi-plus-circle"></i> Thêm thương hiệu
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addBrandLabel">Thêm Thương Hiệu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('brandAdd') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name">Tên thương hiệu</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary ms-2">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>ID</th>
                        <th>Tên Thương Hiệu</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->name }}</td>
                        <td class="action-icons">
                            <a href="{{ route('brandEdit', $brand->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('brandDelete', $brand->id) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')">Xóa</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $brands->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection