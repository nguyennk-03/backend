@extends('admin.layout')
@section('titlepage', 'Cập nhật sản phẩm')
@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="header-title">Cập nhật sản phẩm</h4>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('productUpdate', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Mô tả</label>
            <textarea name="description" class="form-control" id="description" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="price">Giá</label>
            <input type="number" name="price" class="form-control" id="price" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" class="form-control" id="image">
            @if($product->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100px">
            </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

@endsection
