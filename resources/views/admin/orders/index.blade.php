@extends('admin.layout')
@section('title', 'Quản lý Đơn hàng')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i> Quản lý Đơn hàng</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Đơn hàng</li>
                </ol>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body p-4">
                    <form action="{{ route('don-hang.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-search me-1"></i> Tìm
                                    kiếm</label>
                                <input type="text" name="search" class="form-control form-control-sm border-0 shadow-sm"
                                    placeholder="Mã đơn hàng hoặc tên khách" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="fas fa-filter me-1"></i> Trạng
                                    thái</label>
                                <select name="status" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Tất cả --</option>
                                    @foreach (['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipped' => 'Đã
                                    giao', 'completed' => 'Hoàn tất', 'canceled' => 'Đã hủy'] as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 d-flex gap-3">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('don-hang.index') }}"
                                    class="btn btn-warning btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-sync me-1"></i> Làm mới
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end justify-content-end">
            <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal"
                data-bs-target="#addOrderModal">
                <i class="fas fa-plus me-1"></i> Thêm đơn hàng
            </button>
        </div>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="OrderTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Khách hàng</th>
                            <th class="text-center py-3">Tổng tiền</th>
                            <th class="text-center py-3">Trạng thái</th>
                            <th class="text-center py-3">Ngày đặt</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->id }}</td>
                            <td>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</td>
                            <td class="text-end">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                            <td class="text-center">
                                @php
                                $statusClasses = [
                                'completed' => 'bg-success',
                                'pending' => 'bg-warning',
                                'shipped' => 'bg-primary',
                                'canceled' => 'bg-danger',
                                'processing' => 'bg-secondary',
                                ];
                                $statusClass = $statusClasses[$order->status->value] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status->value) }}</span>
                            </td>
                            <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('don-hang.show', $order->id) }}"
                                        class="btn btn-warning btn-sm shadow-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('don-hang.edit', $order->id) }}"
                                        class="btn btn-info btn-sm shadow-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('don-hang.destroy', $order->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Không có đơn hàng nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addOrderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addOrderModalLabel">
                        <i class="fas fa-cart-plus me-2"></i> Thêm đơn hàng mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('don-hang.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Khách hàng</label>
                                <select name="user_id" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                        ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control border-0 shadow-sm"
                                    value="{{ old('phone') }}" placeholder="Nhập số điện thoại" required>
                                @error('phone')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Địa chỉ giao hàng</label>
                                <textarea name="address" class="form-control border-0 shadow-sm" rows="2"
                                    placeholder="Nhập địa chỉ giao hàng" required>{{ old('address') }}</textarea>
                                @error('address')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Sản phẩm</label>
                                <select name="products" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                        ({{ $product->price }})</option>
                                    @endforeach
                                </select>
                                @error('products')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tổng tiền (VNĐ)</label>
                                <input type="text" name="total_price" id="total_price"
                                    class="form-control border-0 shadow-sm" readonly>
                                @error('total_price')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" class="form-select border-0 shadow-sm">
                                    @foreach (['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipped' => 'Đã
                                    giao', 'completed' => 'Hoàn tất', 'canceled' => 'Đã hủy'] as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'pending') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ghi chú</label>
                                <textarea name="note" class="form-control border-0 shadow-sm" rows="3"
                                    placeholder="Nhập ghi chú (nếu có)">{{ old('note') }}</textarea>
                                @error('note')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold"
                                data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu đơn hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection