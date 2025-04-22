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
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Trang Quản Lý</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('don-hang.index') }}">Đơn Hàng</a></li>
                    <li class="breadcrumb-item active">Chi Tiết Đơn Hàng</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">

        {{-- Thông tin người nhận --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-person-circle text-primary"></i> Thông Tin Người Nhận
                    </h5>
                    <p><strong>Tên:</strong> {{ $order->recipient_name }}</p>
                    <p><strong>Điện thoại:</strong> {{ $order->recipient_phone }}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                </div>
            </div>
        </div>

        {{-- Thông tin đơn hàng --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle text-warning"></i> Thông Tin Đơn Hàng
                    </h5>
                    <p><strong>Mã đơn hàng:</strong> #{{ $order->code }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Mã vận đơn:</strong> {{ $order->tracking_code ?? 'Chưa cập nhật' }}</p>

                    <p><strong>Trạng thái đơn hàng:</strong>
                        <span class="badge {{ $order->status_enum->badgeClass() }}">
                            <i class="{{ $order->status_enum->iconClass() }}"></i>
                            {{ $order->status_enum->label() }}
                        </span>
                    </p>

                    <p><strong>Trạng thái thanh toán:</strong>
                        <span class="badge {{ $order->payment_status_enum->badgeClass() }}">
                            <i class="{{ $order->payment_status_enum->iconClass() }}"></i>
                            {{ $order->payment_status_enum->label() }}
                        </span>
                    </p>

                    <p><strong>Phương thức thanh toán:</strong>
                        {{ $order->payment?->name ?? 'Chưa chọn' }}
                    </p>

                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }}₫</p>

                    @if($order->total_after_discount)
                    <p><strong>Giảm giá:</strong> -{{ number_format($order->total_price - $order->total_after_discount) }}₫</p>
                    <p><strong>Thành tiền:</strong> {{ number_format($order->total_after_discount) }}₫</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sản phẩm trong đơn hàng --}}
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-box-seam text-success"></i> Sản Phẩm Đặt Mua
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->variant->product->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                        $variant = $item->variant;
                                        $size = $variant->size->name ?? 'Không có';
                                        $color = $variant->color->name ?? 'Không có';
                                        @endphp
                                        {{ "Size: $size, Màu: $color" }}
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price) }}₫</td>
                                    <td>{{ number_format($item->price * $item->quantity) }}₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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