@extends('admin.layout')
@section('titlepage', 'Cập nhật sản phẩm')
@section('content')

<div class="container-fluid">
    <!-- Tiêu đề -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h3 class="fw-bold text-uppercase">Cập nhật sản phẩm</h3>
        </div>
    </div>

    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Hiển thị lỗi validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form cập nhật sản phẩm -->
    <div class="card shadow-lg rounded">
        <div class="card-body">
            <form action="{{ route('productUpdate', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Danh mục -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-list-alt"></i> Danh mục</label>
                        <select name="category_id" class="form-select form-select-lg" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $product->category_id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Thương hiệu -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-tags"></i> Thương hiệu</label>
                        <select name="brand_id" class="form-select form-select-lg" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach ($brands as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $product->brand_id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tên sản phẩm -->
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label fw-bold"><i class="fas fa-box"></i> Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control form-control-lg" id="name" 
                            value="{{ old('name', $product->name) }}" required>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label fw-bold"><i class="fas fa-align-left"></i> Mô tả</label>
                        <textarea name="description" class="form-control form-control-lg" id="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-bold"><i class="fas fa-dollar-sign"></i> Giá</label>
                        <input type="number" name="price" class="form-control form-control-lg" id="price"
                            value="{{ old('price', $product->price) }}" required>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-image"></i> Hình ảnh</label>
                        <input type="file" name="image" class="form-control form-control-lg">
                        @if($product->image)
                            <div class="mt-3 text-center">
                                <img src="{{ asset($product->image) }}" alt="Ảnh sản phẩm" class="img-thumbnail shadow-lg" width="150">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Nút cập nhật -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Cập nhật sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
