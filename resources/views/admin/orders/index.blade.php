@extends('admin.layout')
@section('titlepage', 'Quản lý Đơn hàng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Quản lý Đơn hàng</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-lg mb-3">
            <div class="card-body">
                <form action="{{ route('don-hang.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nhập mã đơn hàng hoặc tên khách" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">-- Tất cả --</option>
                                @foreach (['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipped' => 'Đã giao', 'completed' => 'Hoàn tất', 'canceled' => 'Đã hủy'] as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tìm kiếm</button>
                            <a href="{{ route('don-hang.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i>
                                Làm
                                mới</a>
                        </div>

                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addOrderModal">
                                <i class="fas fa-plus"></i> Thêm đơn hàng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <h4 class="header-title font-weight-bold mb-3">Danh Sách Đơn Hàng</h4>
                <table id="OrderTable" class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="text-center">{{ $order->id }}</td>
                                <td>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</td>
                                <td class="text-end">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                                <td class="text-center">
                                    <span class="badge @if($order->status->value == 'completed') badge-success
                                    @elseif($order->status->value == 'pending') badge-warning
                                    @elseif($order->status->value == 'shipped') badge-primary
                                    @elseif($order->status->value == 'canceled') badge-danger
                                    @else badge-secondary @endif">
                                        {{ ucfirst($order->status->value) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('don-hang.show', $order->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('don-hang.edit', $order->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('don-hang.destroy', $order->id) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Đơn Hàng -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addOrderModalLabel">Thêm Đơn Hàng Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('don-hang.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Khách hàng -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Khách hàng</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <!-- Địa chỉ giao hàng -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>

                            <!-- Sản phẩm -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Chọn sản phẩm</label>
                                <select name="products[]" class="form-select select2" multiple="multiple" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tổng tiền -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tổng tiền (VNĐ)</label>
                                <input type="text" name="total_price" id="total_price" class="form-control" readonly>
                            </div>

                            <!-- Trạng thái đơn hàng -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="shipped">Đã giao</option>
                                    <option value="completed">Hoàn tất</option>
                                    <option value="canceled">Đã hủy</option>
                                </select>
                            </div>

                            <!-- Ghi chú -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu đơn hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection