@extends('admin.layout')
@section('title', 'Quản lý màu sắc')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="fas fa-box-open me-2"></i> Quản lý màu sắc</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Màu sắc</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form thêm màu -->
                    <div class="mb-4 p-4 border rounded">
                        <h5 class="mb-3">Thêm Màu</h5>
                        <form action="{{ route('mau-sac.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">Tên màu</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="hex_code" class="form-label">Chọn mã màu</label>
                                    <input type="color" class="form-control form-control-color" id="hex_code" name="hex_code" value="{{ old('hex_code', '#000000') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Thêm màu</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bảng danh sách màu -->
                    <div class="mt-4">
                        <h5 class="mb-3">Bảng Màu</h5>
                        <div class="table-responsive">
                            <table id="ColorTable" class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th>Tên màu</th>
                                        <th width="10%">Mã màu</th>
                                        <th>Trạng thái</th>
                                        <th width="15%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($colors as $index => $color)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $color->name }}</td>
                                        <td class="text-center">
                                            @if($color->hex_code)
                                                <div class="color-box" style="background-color: {{ $color->hex_code }}; width: 30px; height: 30px; border-radius: 4px; border: 1px solid #ccc;" title="{{ $color->hex_code }}"></div>
                                            @else
                                                <span class="text-secondary">❌</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('mau-sac.update', $color->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="is_active" onchange="this.form.submit()" class="form-select form-select-sm custom-status-select">
                                                    <option value="1" {{ $color->is_active ? 'selected' : '' }}>Hiển thị</option>
                                                    <option value="0" {{ !$color->is_active ? 'selected' : '' }}>Ẩn</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('mau-sac.destroy', $color->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa màu này?')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chưa có màu nào được thêm</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection