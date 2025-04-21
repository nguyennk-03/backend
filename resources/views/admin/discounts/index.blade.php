@extends('admin.layout')
@section('title', 'Quản lý Giảm Giá')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title"><i class="la la-percent"></i>Quản Lý Giảm Giá</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Giảm Giá</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Tìm kiếm & Lọc giảm giá -->
    <div class="card shadow-sm rounded-lg mb-3">
        <div class="card-body">
            <form action="{{ route('khuyen-mai.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Nhập tên mã giảm giá"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Loại giảm giá</label>
                        <select name="type" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Giảm theo %
                            </option>
                            <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Giảm giá tiền
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('khuyen-mai.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync"></i> Làm mới
                        </a>
                    </div>

                    <!-- Nút Thêm Mã Giảm Giá -->
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#addDiscountModal">
                            <i class="fas fa-plus"></i> Thêm giảm giá
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách Mã Giảm Giá -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <h4 class="header-title font-weight-bold mb-3">Danh Sách Giảm Giá</h4>
            <table id="DiscountTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Mã giảm giá</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày hết hạn</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($discounts as $discount)
                    <tr>
                        <td class="text-center">{{ $discount->id }}</td>
                        <td>{{ $discount->code }}</td>
                        <td class="text-center">
                            {{ $discount->discount_type == 'percentage' ? 'Giảm %' : 'Giảm tiền' }}
                        </td>
                        <td>
                            {{ $discount->discount_type == 'percentage'
                                        ? round($discount->value) . '%'
                                        : number_format(ceil($discount->value / 1000) * 1000, 0, ',', '.') . ' VNĐ' }}
                        </td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($discount->start_date)) }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($discount->end_date)) }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('khuyen-mai.edit', $discount->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('khuyen-mai.destroy', $discount->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có mã giảm giá nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Mã Giảm Giá -->
<div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDiscountModalLabel">Thêm Mã Giảm Giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('khuyen-mai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã giảm giá</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại giảm giá</label>
                        <select name="type" class="form-select" required>
                            <option value="percentage">Giảm theo %</option>
                            <option value="fixed">Giảm tiền</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá trị</label>
                        <input type="number" name="value" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày bắt đầu</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày hết hạn</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu mã giảm giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection