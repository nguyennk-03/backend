@extends('admin.layout')
@section('title', 'Chi tiết đơn hàng')
@section('content')

    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('orders') }}">Đơn hàng</a></li>
                            <li class="breadcrumb-item active">Chi tiết</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Chi tiết đơn hàng #{{ $order->id }}</h4>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <h4 class="header-title font-weight-bold mb-3">Thông tin đơn hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Mã đơn hàng:</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Khách hàng:</th>
                        <td>
                            @if($order->user)
                                <a href="{{ route('users.show', $order->user->id) }}">{{ $order->user->name }}</a>
                                ({{ $order->user->email }})
                            @else
                                <span class="text-muted">Khách vãng lai</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ optional($order->user)->phone ?? $order->phone }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ giao hàng:</th>
                        <td>{{ optional($order->user)->address ?? $order->address ?? 'Chưa có địa chỉ' }}</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền:</th>
                        <td><strong class="text-danger">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @php
                                $statusMap = [
                                    'completed' => ['Hoàn thành', 'badge-success'],
                                    'pending' => ['Chờ xử lý', 'badge-warning'],
                                    'shipped' => ['Đã giao hàng', 'badge-primary'],
                                    'canceled' => ['Đã hủy', 'badge-danger'],
                                ];
                                $status = $statusMap[$order->status] ?? ['Không xác định', 'badge-secondary'];
                            @endphp
                            <span class="badge {{ $status[1] }}">{{ $status[0] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày đặt hàng:</th>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card shadow-sm rounded-lg mt-3">
            <div class="card-body">
                <h4 class="header-title font-weight-bold mb-3">Danh sách sản phẩm</h4>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($order->items->isNotEmpty())
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        @if(optional($item->product)->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="Hình ảnh sản phẩm"
                                                width="60" height="60" class="rounded">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->id) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Sản phẩm không tồn tại</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                    <td><strong>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có sản phẩm nào</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Thao tác -->
        <div class="d-flex justify-content-end me-2 mt-3">
            <a href="{{ route('orders') }}" class="btn btn-secondary">Quay lại</a>

            @if($order->status != 'completed' && $order->status != 'canceled')
                <div>
                    <a href="{{ route('orderedit', $order->id) }}" class="btn btn-info">Chỉnh sửa</a>
                    <form action="{{ route('orderdelete', $order->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

@endsection