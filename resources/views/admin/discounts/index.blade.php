```blade
@extends('admin.layout')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-ticket me-2"></i>Quản Lý Mã Giảm Giá</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Mã giảm giá</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Message -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="card shadow-sm rounded-lg">
            <div class="card-body p-4">
                <form action="{{ route('giam-gia.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-eye me-1"></i> Trạng thái</label>
                            <select name="is_active" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-tags me-1"></i> Loại giảm giá</label>
                            <select name="discount_type" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="0" {{ request('discount_type') == '0' ? 'selected' : '' }}>Phần trăm</option>
                                <option value="1" {{ request('discount_type') == '1' ? 'selected' : '' }}>Số tiền cố định</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-calendar-alt me-1"></i> Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control form-control-sm border-0 shadow-sm" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="fas fa-calendar-alt me-1"></i> Ngày kết thúc</label>
                            <input type="date" name="end_date" class="form-control form-control-sm border-0 shadow-sm" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('giam-gia.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                        <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
                            <i class="fas fa-plus me-1"></i> Thêm mã giảm giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Discount Modal -->
    <div class="modal fade" id="addDiscountModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addDiscountModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Thêm mã giảm giá mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('giam-gia.store') }}" method="POST" id="addDiscountForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Tên mã giảm giá</label>
                                <input type="text" name="name" id="name" class="form-control border-0 shadow-sm" value="{{ old('name') }}" placeholder="Nhập tên mã giảm giá" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label fw-semibold">Mã giảm giá</label>
                                <input type="text" name="code" id="code" class="form-control border-0 shadow-sm" value="{{ old('code') }}" placeholder="Nhập mã giảm giá" required>
                                @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label fw-semibold">Loại giảm giá</label>
                                <select name="discount_type" id="discount_type" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('discount_type') == '0' ? 'selected' : '' }}>Phần trăm</option>
                                    <option value="1" {{ old('discount_type') == '1' ? 'selected' : '' }}>Số tiền cố định</option>
                                </select>
                                @error('discount_type') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="value" class="form-label fw-semibold">Giá trị giảm</label>
                                <input type="number" name="value" id="value" class="form-control border-0 shadow-sm" value="{{ old('value') }}" placeholder="Nhập giá trị giảm" min="0" step="0.01" required>
                                @error('value') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="min_order_amount" class="form-label fw-semibold">Số tiền tối thiểu</label>
                                <input type="number" name="min_order_amount" id="min_order_amount" class="form-control border-0 shadow-sm" value="{{ old('min_order_amount') }}" placeholder="Nhập số tiền tối thiểu" min="0" step="0.01" required>
                                @error('min_order_amount') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="usage_limit" class="form-label fw-semibold">Giới hạn sử dụng</label>
                                <input type="number" name="usage_limit" id="usage_limit" class="form-control border-0 shadow-sm" value="{{ old('usage_limit') }}" placeholder="Nhập giới hạn sử dụng (để trống nếu không giới hạn)" min="0">
                                @error('usage_limit') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control border-0 shadow-sm" value="{{ old('start_date') }}" required>
                                @error('start_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control border-0 shadow-sm" value="{{ old('end_date') }}" required>
                                @error('end_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="is_active" class="form-label fw-semibold">Trạng thái</label>
                                <select name="is_active" id="is_active" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                @error('is_active') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu mã giảm giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Giam-gia Table -->
    <div class="card shadow-sm rounded-lg" style="width: 100%; overflow-x: auto;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="DiscountTable" class="table table-striped table-hover align-middle" style="width: 100%; font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="text-center py-2">ID</th>
                            <th class="text-center py-2">Tên</th>
                            <th class="text-center py-2">Mã</th>
                            <th class="text-center py-2">Loại</th>
                            <th class="text-center py-2">Giá trị</th>
                            <th class="text-center py-2">Số tiền tối thiểu</th>
                            <th class="text-center py-2">Ngày bắt đầu</th>
                            <th class="text-center py-2">Ngày kết thúc</th>
                            <th class="text-center py-2">Trạng thái</th>
                            <th class="text-center py-2">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($discounts as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">{{ $item->name }}</td>
                            <td class="text-center">{{ $item->code }}</td>
                            <td class="text-center">{{ $item->discount_type == 0 ? 'Phần trăm' : 'Số tiền cố định' }}</td>
                            <td class="text-end">{{ $item->discount_type == 0 ? number_format($item->value, 2) . '%' : number_format($item->value * 10, 0, ',', '.') . '₫' }}</td>
                            <td class="text-end">{{ number_format($item->min_order_amount * 100, 0, ',', '.') }}₫</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#showDiscountModal{{ $item->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#editDiscountModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('giam-gia.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Không có mã giảm giá nào để hiển thị.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Show Discount Modal -->
    @foreach ($discounts as $item)
    <div class="modal fade" id="showDiscountModal{{ $item->id }}" tabindex="-1" aria-labelledby="showDiscountModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-gradient text-white">
                    <h5 class="modal-title fw-bold" id="showDiscountModalLabel{{ $item->id }}">
                        <i class="fas fa-info-circle me-2"></i> Chi tiết mã giảm giá #{{ $item->id }}
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-3"><strong>Tên:</strong> {{ $item->name }}</p>
                                    <p class="mb-3"><strong>Mã:</strong> {{ $item->code }}</p>
                                    <p class="mb-3"><strong>Loại giảm giá:</strong> {{ $item->discount_type == 0 ? 'Phần trăm' : 'Số tiền cố định' }}</p>
                                    <p class="mb-3"><strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-3"><strong>Giá trị:</strong> {{ $item->discount_type == 0 ? number_format($item->value, 2) . '%' : number_format($item->value, 0, ',', '.') . '₫' }}</p>
                                    <p class="mb-3"><strong>Số tiền tối thiểu:</strong> {{ number_format($item->min_order_amount, 0, ',', '.') }}₫</p>
                                    <p class="mb-3"><strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y H:i') }}</p>
                                    <p class="mb-3"><strong>Trạng thái:</strong> <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">{{ $item->is_active ? 'Hoạt động' : 'Không hoạt động' }}</span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-3"><strong>Giới hạn sử dụng:</strong> {{ $item->usage_limit ?? 'Không giới hạn' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit Discount Modal -->
    <div class="modal fade" id="editDiscountModal{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDiscountModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editDiscountModalLabel{{ $item->id }}">
                        <i class="fas fa-edit me-2"></i> Sửa mã giảm giá
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('giam-gia.update', $item->id) }}" method="POST" id="editDiscountForm{{ $item->id }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="edit_name{{ $item->id }}" class="form-label fw-semibold">Tên mã giảm giá</label>
                                <input type="text" name="name" id="edit_name{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('name', $item->name) }}" placeholder="Nhập tên mã giảm giá" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_code{{ $item->id }}" class="form-label fw-semibold">Mã giảm giá</label>
                                <input type="text" name="code" id="edit_code{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('code', $item->code) }}" placeholder="Nhập mã giảm giá" required>
                                @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_discount_type{{ $item->id }}" class="form-label fw-semibold">Loại giảm giá</label>
                                <select name="discount_type" id="edit_discount_type{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="0" {{ old('discount_type', $item->discount_type) == 0 ? 'selected' : '' }}>Phần trăm</option>
                                    <option value="1" {{ old('discount_type', $item->discount_type) == 1 ? 'selected' : '' }}>Số tiền cố định</option>
                                </select>
                                @error('discount_type') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_value{{ $item->id }}" class="form-label fw-semibold">Giá trị giảm</label>
                                <input type="number" name="value" id="edit_value{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('value', $item->value) }}" placeholder="Nhập giá trị giảm" min="0" step="0.01" required>
                                @error('value') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_min_order_amount{{ $item->id }}" class="form-label fw-semibold">Số tiền tối thiểu</label>
                                <input type="number" name="min_order_amount" id="edit_min_order_amount{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('min_order_amount', $item->min_order_amount) }}" placeholder="Nhập số tiền tối thiểu" min="0" step="0.01" required>
                                @error('min_order_amount') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_usage_limit{{ $item->id }}" class="form-label fw-semibold">Giới hạn sử dụng</label>
                                <input type="number" name="usage_limit" id="edit_usage_limit{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('usage_limit', $item->usage_limit) }}" placeholder="Nhập giới hạn sử dụng (để trống nếu không giới hạn)" min="0">
                                @error('usage_limit') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_start_date{{ $item->id }}" class="form-label fw-semibold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" id="edit_start_date{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('start_date', $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y-m-d\TH:i') : '') }}" required>
                                @error('start_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_end_date{{ $item->id }}" class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" id="edit_end_date{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('end_date', $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('Y-m-d\TH:i') : '') }}" required>
                                @error('end_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_is_active{{ $item->id }}" class="form-label fw-semibold">Trạng thái</label>
                                <select name="is_active" id="edit_is_active{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('is_active', $item->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('is_active', $item->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                @error('is_active') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</div>
@endsection