@extends('admin.layout')
@section('title', 'Chi Tiết Đơn Hàng')
@section('content')

<div class="container-fluid">
    <!-- Tiêu đề -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold">
                    <i class="bi bi-receipt-cutoff"></i> Chi Tiết Đơn Hàng #{{ $order->id }}
                </h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('don-hang.index') }}">Đơn Hàng</a></li>
                    <li class="breadcrumb-item active">Chi Tiết Đơn Hàng</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Thông tin đơn hàng -->
    <div class="row g-4 mt-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-circle text-primary"></i> Thông Tin Khách Hàng</h5>
                    <p><strong>Tên: </strong> {{ $order->user->name }}</p>
                    <p><strong>Email: </strong> {{ $order->user->email }}</p>
                    <p><strong>Điện thoại: </strong> {{ $order->user->phone ?? 'Chưa có' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-info-circle text-warning"></i> Thông Tin Đơn Hàng</h5>
                    <p><strong>Mã đơn hàng: </strong> #{{ $order->id }}</p>
                    <p><strong>Ngày đặt hàng: </strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái: </strong> 
                        <span class="badge bg-{{ $order->status }}">
                            {{ $order->status_text }}
                        </span>
                    </p>
                    <p><strong>Tổng tiền: </strong> {{ number_format($order->total_price) }}₫</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm trong đơn hàng -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-cart-check text-success"></i> Sản Phẩm Trong Đơn Hàng</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Ảnh</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" width="60">
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price) }}₫</td>
                                        <td>{{ number_format($item->quantity * $item->price) }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('don-hang.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 12px;
    }

    .hover-scale:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
</style>

@endsection
