@extends('admin.layout')
@section('title', 'Quản lý Đơn hàng')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-shopping-cart me-2"></i> Quản Lý Đơn Hàng</h4>
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
        <div class="card shadow-sm rounded-lg">
            <div class="card-body p-4">
                <form action="{{ route('don-hang.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="fas fa-search me-1"></i> Tìm kiếm</label>
                            <input type="text" name="search" class="form-control form-control-sm border-0 shadow-sm"
                                placeholder="Mã đơn hàng hoặc tên khách" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-filter me-1"></i> Trạng thái</label>
                            <select name="status" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                @foreach (App\Enums\OrderStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('don-hang.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                                <i class="fas fa-sync me-1"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="OrderTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ngày đặt</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td>{{ e($item->code) }}</td>
                            <td>{{ e($item->user->name ?? 'Khách vãng lai') }}</td>
                            <td class="text-end">{{ number_format($item->total_price, 0, ',', '.') }} đ</td>
                            <td class="text-center">
                                <form action="{{ route('don-hang.update', $item->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        @foreach(\App\Enums\OrderStatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}"
                                            {{ $item->status->value === $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có đơn hàng nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết đơn hàng -->
    @foreach ($orders as $item)
    <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="showModalLabel{{ $item->id }}">
                        <i class="bi bi-receipt"></i> Chi tiết đơn hàng #{{ e($item->code) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">

                    <!-- Thông tin khách hàng & đơn hàng -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded shadow-sm">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-circle"></i> Thông tin khách hàng</h6>
                                <p><strong>Tên:</strong> {{ e($item->user->name ?? 'Khách vãng lai') }}</p>
                                <p><strong>Email:</strong> {{ e($item->user->email ?? 'N/A') }}</p>
                                <p><strong>Điện thoại:</strong> {{ e($item->recipient_phone ?? $item->user->phone ?? 'N/A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded shadow-sm">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle"></i> Thông tin đơn hàng</h6>
                                <p><strong>Mã đơn:</strong> {{ e($item->code) }}</p>
                                <p><strong>Trạng thái:</strong>
                                    <span class="badge {{ $item->status->badgeClass() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </p>
                                <p><strong>Thanh toán:</strong>
                                    <span class="badge {{ $item->payment_status->badgeClass() }}">
                                        {{ $item->payment_status->label() }}
                                    </span>
                                </p>
                                <p><strong>Phương thức:</strong> {{ e($item->payment->name ?? 'Không xác định') }}</p>
                                <p><strong>Mã vận đơn:</strong> {{ e($item->tracking_code ?? 'N/A') }}</p>
                                <p><strong>Ngày đặt:</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Địa chỉ giao hàng -->
                    <div class="mb-4 p-3 border rounded shadow-sm">
                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-geo-alt"></i> Địa chỉ giao hàng</h6>
                        <p>{{ e($item->shipping_address ?? 'Không có địa chỉ') }}</p>
                        <p><strong>Người nhận:</strong> {{ e($item->recipient_name ?? 'N/A') }}</p>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-box-seam"></i> Danh sách sản phẩm</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Thông tin</th>
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->items as $items)
                                <tr>
                                    <td>
                                        @if ($items->product && $items->product->image)
                                        <img src="{{ asset('storage/' . $items->product->image) }}" alt="Hình ảnh" width="60" class="rounded">
                                        @else
                                        <span class="text-muted fst-italic">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $items->product->name ?? 'Sản phẩm đã xóa' }}</td>
                                    <td>
                                        Màu: <span style="color: {{ $items->product->color->hex_code ?? '#000' }}">
                                            {{ $items->product->color->name ?? 'N/A' }}
                                        </span><br>
                                        Size: {{ $items->product->size->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $items->quantity }}</td>
                                    <td>{{ number_format($items->price, 0, ',', '.') }} đ</td>
                                    <td class="fw-bold text-end">{{ number_format($items->quantity * $items->price, 0, ',', '.') }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="text-end mt-4">
                        <p class="fs-5"><strong>Tổng tiền:</strong> <span class="text-danger">{{ number_format($item->total_price *100, 0, ',', '.') }} đ</span></p>
                        @if ($item->discount)
                        <p><strong>Giảm giá:</strong> {{ e($item->discount->code) }} - {{ $item->discount->value }}{{ $item->discount->type === 'percent' ? '%' : 'đ' }}</p>
                        <p class="fs-5"><strong>Sau giảm:</strong> <span class="text-success">{{ number_format($item->total_after_discount, 0, ',', '.') }} đ</span></p>
                        @endif
                    </div>

                    <!-- Ghi chú -->
                    @if ($item->note)
                    <div class="mt-3 p-3 border-start border-4 border-info bg-light">
                        <h6 class="fw-bold"><i class="bi bi-pencil-square"></i> Ghi chú</h6>
                        <p>{{ e($item->note) }}</p>
                    </div>
                    @endif

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</div>
@endsection