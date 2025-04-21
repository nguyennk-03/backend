@extends('admin.layout')
@section('title', 'Quản lý kích thước')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-ruler-combined me-2"></i> Quản Lý Kích Thước</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Kích thước</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form thêm kích thước -->
                    <div class="mb-4 p-4 border rounded">
                        <h5 class="mb-3">Thêm kích thước</h5>
                        <form action="{{ route('kich-thuoc.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label name="name" class="form-label">Tên size (EU)</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label name="cm" class="form-label">Chiều dài (cm)</label>
                                    <input type="number" step="0.1" class="form-control" id="cm" name="cm"
                                        value="{{ old('cm') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Thêm kích thước</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bảng danh sách kích thước -->
                    <div class="mt-4">
                        <h5 class="mb-3">Danh sách kích thước</h5>
                        <div class="table-responsive">
                            <table id="SizeTable" class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th>Tên Size</th>
                                        <th>Chiều dài (cm)</th>
                                        <th>Trạng thái</th>
                                        <th width="15%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizes as $index => $size)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $size->name }}</td>
                                        <td>{{ $size->cm ?? '—' }}</td>
                                        <td>
                                            <form action="{{ route('kich-thuoc.update', $size->id) }}" method="POST" class="status-form d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $size->name }}">
                                                <input type="hidden" name="cm" value="{{ $size->cm }}">
                                                <select name="is_active" onchange="this.form.submit()" class="form-select form-select-sm custom-status-select">
                                                    <option value="1" {{ $size->is_active ? 'selected' : '' }}>Hiển thị</option>
                                                    <option value="0" {{ !$size->is_active ? 'selected' : '' }}>Ẩn</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('kich-thuoc.destroy', $size->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Bạn có chắc muốn xóa size này?')">Xóa</button>
                                            </form>
                                            <!-- Có thể thêm nút sửa tại đây -->
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($sizes->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chưa có kích thước nào được thêm
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end bảng -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection