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
                        <div class="col-md-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addOrderModal">
                                <i class="fas fa-plus me-1"></i> Thêm đơn hàng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
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
                                <label class="form-label fw-semibold">Khách hàng <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control border-0 shadow-sm"
                                    value="{{ old('phone') }}" placeholder="Nhập số điện thoại" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control border-0 shadow-sm" rows="2"
                                    placeholder="Nhập địa chỉ giao hàng" required>{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sản phẩm <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-select border-0 shadow-sm" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ number_format($product->price, 0, ',', '.') }} VNĐ)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control border-0 shadow-sm"
                                    value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tổng tiền (VNĐ)</label>
                                <input type="text" name="total_price" id="total_price" class="form-control border-0 shadow-sm" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select border-0 shadow-sm" required>
                                    @foreach (App\Enums\OrderStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ghi chú</label>
                                <textarea name="note" class="form-control border-0 shadow-sm" rows="3"
                                    placeholder="Nhập ghi chú (nếu có)">{{ old('note') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu đơn hàng</button>
                        </div>
                    </form>
                </div>
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
                        @forelse ($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->id }}</td>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                            <td class="text-end">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                            <td class="text-center">
                                <form action="{{ route('don-hang.update', $order->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        @foreach(\App\Enums\OrderStatusEnum::cases() as $status)
                                        <option
                                            value="{{ $status->value }}"
                                            {{ $order->status->value === $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#showModal{{ $order->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal xem chi tiết đơn hàng -->
                        <div class="modal fade" id="showModal{{ $order->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalLabel{{ $order->id }}">Chi tiết đơn hàng #{{ $order->code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="list-group">
                                            @foreach ($order->items as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                {{ $item->product_name }} x {{ $item->quantity }}
                                                <span>{{ number_format($item->price, 0, ',', '.') }} đ</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <hr>
                                        <p class="text-end fw-bold">Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} đ</p>
                                        @if ($order->total_after_discount)
                                        <p class="text-end text-success fw-bold">Sau giảm: {{ number_format($order->total_after_discount, 0, ',', '.') }} đ</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <div class="modal fade" id="showModal{{ $order->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="showModalLabel{{ $order->id }}">Chi tiết đơn hàng #{{ $order->code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    {{-- Thông tin khách hàng & đơn hàng --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Thông tin khách hàng</strong></h6>
                            <p>Tên: {{ $order->user->name ?? 'Khách vãng lai' }}</p>
                            <p>Email: {{ $order->user->email ?? 'N/A' }}</p>
                            <p>Điện thoại: {{ $order->recipient_phone ?? $order->user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Thông tin đơn hàng</strong></h6>
                            <p>Mã đơn: {{ $order->code }}</p>
                            <p>Trạng thái:
                                <span class="badge {{ $order->status->badgeClass() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </p>
                            <p>Trạng thái thanh toán:
                                <span class="badge {{ $order->payment_status->badgeClass() }}">
                                    {{ $order->payment_status->label() }}
                                </span>
                            </p>
                            <p>Phương thức thanh toán: {{ $order->payment->name ?? 'Không xác định' }}</p>
                            <p>Mã vận đơn: {{ $order->tracking_code ?? 'N/A' }}</p>
                            <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Địa chỉ giao hàng --}}
                    <div class="mb-3">
                        <h6><strong>Địa chỉ giao hàng</strong></h6>
                        <p>{{ $order->shipping_address ?? 'Không có địa chỉ' }}</p>
                        <p>Người nhận: {{ $order->recipient_name ?? 'N/A' }}</p>
                    </div>

                    {{-- Danh sách sản phẩm --}}
                    <h6 class="mb-3"><strong>Danh sách sản phẩm</strong></h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tổng tiền, giảm giá, thanh toán --}}
                    <div class="text-end">
                        <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} đ</p>
                        @if ($order->discount)
                        <p><strong>Giảm giá:</strong> {{ $order->discount->code }} - {{ $order->discount->value }}{{ $order->discount->type === 'percent' ? '%' : 'đ' }}</p>
                        <p><strong>Sau giảm:</strong> {{ number_format($order->total_after_discount, 0, ',', '.') }} đ</p>
                        @endif
                    </div>

                    {{-- Ghi chú --}}
                    @if ($order->note)
                    <div class="mt-3">
                        <h6><strong>Ghi chú</strong></h6>
                        <p>{{ $order->note }}</p>
                    </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ẩn thông báo thành công sau 3 giây
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = "opacity 0.5s ease";
                successMessage.style.opacity = "0";
                setTimeout(() => {
                    successMessage.remove();
                }, 500);
            }, 3000);
        }

        // Khởi tạo DataTable
        if ($.fn.DataTable) {
            $('#OrderTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    lengthMenu: "Hiển thị _MENU_ bản ghi mỗi trang",
                    zeroRecords: "Không tìm thấy kết quả",
                    info: "Hiển thị trang _PAGE_ của _PAGES_",
                    infoEmpty: "Không có dữ liệu",
                    infoFiltered: "(lọc từ _MAX_ tổng số bản ghi)",
                    search: "Tìm kiếm:",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Tiếp",
                        previous: "Trước"
                    },
                },
                order: [
                    [0, 'desc']
                ],
                responsive: true
            });
        }

        // Tính toán tổng tiền
        function calculateTotal() {
            const productSelect = document.querySelector('select[name="product_id"]');
            const quantityInput = document.querySelector('input[name="quantity"]');
            const totalPriceInput = document.getElementById('total_price');

            if (productSelect && quantityInput && totalPriceInput) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const total = price * quantity;

                totalPriceInput.value = total > 0 ? total.toLocaleString('vi-VN') + ' VNĐ' : '0 VNĐ';
            }
        }

        // Gắn sự kiện tính toán
        const productSelect = document.querySelector('select[name="product_id"]');
        const quantityInput = document.querySelector('input[name="quantity"]');

        if (productSelect && quantityInput) {
            productSelect.addEventListener('change', calculateTotal);
            quantityInput.addEventListener('input', calculateTotal);
            calculateTotal(); // Tính toán lần đầu khi tải trang
        }
    });
</script>
@endpush