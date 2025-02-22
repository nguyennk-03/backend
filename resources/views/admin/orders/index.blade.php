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

        <!-- Tìm kiếm & Lọc trạng thái -->
        <div class="card shadow-sm rounded-lg mb-3">
            <div class="card-body">
                <form action="{{ route('orders') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <!-- Ô nhập tìm kiếm -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nhập mã đơn hàng hoặc tên khách" value="{{ request('search') }}">
                        </div>

                        <!-- Dropdown lọc trạng thái -->
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

                        <!-- Nút tìm kiếm & làm mới -->
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('orders') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Danh sách đơn hàng -->
        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <h4 class="header-title font-weight-bold mb-3">Danh Sách Đơn Hàng</h4>
                <table class="table table-striped table-bordered align-middle">
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
                                    <span class="badge 
                                        @if($order->status == 'completed') badge-success
                                        @elseif($order->status == 'pending') badge-warning
                                        @elseif($order->status == 'shipped') badge-primary
                                        @elseif($order->status == 'canceled') badge-danger
                                        @else badge-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('orderview', $order->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orderedit', $order->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('orderdelete', $order->id) }}" method="POST"
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

                <!-- Phân trang -->
                <div class="d-flex justify-content-end">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection