@extends('admin.layout')
@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bag-check-fill text-success me-2"></i>Đơn Hàng #{{ $order->code }}
        </h4>
        <a href="{{ route('don-hang.index') }}" class="btn btn-sm btn-outline-dark shadow-sm hover-shadow">
            <i class="bi bi-chevron-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Info Sections -->
    <div class="row g-4 mb-4">
        <!-- Người nhận -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light-subtle">
                <div class="card-body">
                    <h6 class="text-primary fw-semibold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Thông Tin Người Nhận</h6>
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Họ tên:</strong> {{ $order->recipient_name }}</li>
                        <li><strong>Điện thoại:</strong> {{ $order->recipient_phone }}</li>
                        <li><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                        <li><strong>Ghi chú:</strong> {{ $order->note ?: 'Không có' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light-subtle">
                <div class="card-body">
                    <h6 class="text-warning fw-semibold mb-3"><i class="bi bi-info-circle-fill me-2"></i>Thông Tin Đơn Hàng</h6>
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Mã vận đơn:</strong> {{ $order->tracking_code ?: 'Chưa có' }}</li>
                        <li><strong>Trạng thái đơn:</strong>
                            <span class="badge {{ $order->status_enum->badgeClass() }}">
                                <i class="{{ $order->status_enum->iconClass() }}"></i> {{ $order->status_enum->label() }}
                            </span>
                        </li>
                        <li><strong>Thanh toán:</strong>
                            <span class="badge {{ $order->payment_status_enum->badgeClass() }}">
                                <i class="{{ $order->payment_status_enum->iconClass() }}"></i> {{ $order->payment_status_enum->label() }}
                            </span>
                        </li>
                        <li><strong>Phương thức:</strong> {{ $order->payment->name ?? 'Chưa chọn' }}</li>
                        <li><strong>Tổng tiền:</strong> <span class="fw-bold text-dark">{{ number_format($order->total_price) }}₫</span></li>
                        @if($order->total_after_discount && $order->total_after_discount < $order->total_price)
                            <li><strong>Giảm giá:</strong> <span class="text-danger">-{{ number_format(($order->total_price - $order->total_after_discount)) }}₫</span></li>
                            <li><strong>Thành tiền:</strong> <span class="text-success fw-bold">{{ number_format($order->total_after_discount) }}₫</span></li>
                            @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm -->
    <div class="card border-0 shadow rounded-4">
        <div class="card-body">
            <h5 class="fw-semibold text-success mb-3"><i class="bi bi-boxes me-2"></i>Sản Phẩm Đặt Mua</h5>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="table-light text-muted border-bottom">
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th>Màu sắc</th>
                            <th>Kích cỡ</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                        @php
                        $product = $item->product;
                        $product = $product ?? null;
                        @endphp
                        <tr class="border-bottom hover-shadow">
                            <td>{{ $loop->iteration }}</td>
                            <td class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $product->image) }}" class="me-3 rounded" width="45" height="45" style="object-fit:cover;">
                                <div>
                                    <span class="fw-medium">{{ $product->name ?? 'N/A' }}</span><br>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->color)
                                    <span class="me-2 d-inline-block border rounded-circle" style="width:16px; height:16px; background-color:{{ $product->color->hex_code }}"></span>
                                    <span>{{ $product->color->name }}</span>
                                    @else
                                    ---
                                    @endif
                                </div>
                            </td>
                            <td>{{ $product->size->name ?? '---' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->price) }}₫</td>
                            <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity) }}₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Style bổ sung -->
<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .badge i {
        margin-right: 3px;
        vertical-align: middle;
    }

    .card-body h5 i,
    .card-body h6 i {
        vertical-align: middle;
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .shadow-sm {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection