@extends('admin.layout')
@section('titlepage', 'Chỉnh sửa đơn hàng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('orders') }}">Đơn hàng</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Chỉnh sửa đơn hàng #{{ $order->id }}</h4>
                </div>
            </div>
        </div>

        <!-- Hiển thị thông báo -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('orderupdate', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm rounded-lg">
                <div class="card-body">
                    <h4 class="header-title font-weight-bold mb-3">Thông tin khách hàng</h4>

                    <div class="mb-3">
                        <label class="form-label">Tên khách hàng:</label>
                        <input type="text" class="form-control"
                            value="{{ optional($order->user)->name ?? 'Khách vãng lai' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control"
                            value="{{ optional($order->user)->phone ?? $order->phone ?? 'Không có số điện thoại' }}"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ giao hàng:</label>
                        <input type="text" class="form-control"
                            value="{{ optional($order->user)->address ?? $order->address ?? 'Không có địa chỉ' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn hàng:</label>
                        <select name="status" class="form-select">
                            @foreach(['pending' => 'Chờ xử lý', 'shipped' => 'Đang giao', 'completed' => 'Hoàn tất', 'canceled' => 'Đã hủy'] as $key => $label)
                                <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

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
                            @if($order->items->count() > 0)
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if(optional($item->product)->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="Hình ảnh" width="50">
                                            @else
                                                <span class="text-muted">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($item->product)->name ?? 'Sản phẩm không tồn tại' }}
                                        </td>
                                        <td>
                                            <input type="number" name="quantities[{{ $item->id }}]" class="form-control"
                                                value="{{ $item->quantity }}" min="1">
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Không có sản phẩm nào</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thao tác -->
            <div class="d-flex justify-content-end me-2 mt-3">
                <a href="{{ route('orders') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

@endsection