@extends('admin.layout')
@section('title', 'Quản lý Đánh Giá')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title"><i class="la la-award"></i> Quản Lý Đánh Giá</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Đánh Giá</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Bộ lọc & tìm kiếm -->
    <div class="card shadow-sm rounded-lg mb-3">
        <div class="card-body">
            <form action="{{ route('danh-gia.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Nhập tên khách hàng hoặc sản phẩm" value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Số sao</label>
                        <select name="rating" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} ⭐
                                </option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('danh-gia.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách đánh giá -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <h4 class="header-title font-weight-bold mb-3">Danh Sách Đánh Giá</h4>
            <table id="ReviewTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                    <tr>
                        <td class="text-center">{{ $review->id }}</td>
                        <td>{{ $review->user->name ?? 'Ẩn danh' }}</td>
                        <td>{{ $review->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                        <td class="text-center">{{ $review->rating }} ⭐</td>
                        <td>{{ $review->comment }}</td>
                        <td class="text-center">
                            <form action="{{ route('danh-gia.update', $review->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-center">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#showModal{{ $review->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có đánh giá nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal xem chi tiết đánh giá -->
    @foreach ($reviews as $review)
    <div class="modal fade" id="showModal{{ $review->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $review->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel{{ $review->id }}">Chi tiết Đánh Giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3"><strong>ID:</strong> {{ $review->id }}</div>
                    <div class="mb-3"><strong>Khách hàng:</strong> {{ $review->user->name ?? 'Ẩn danh' }}</div>
                    <div class="mb-3"><strong>Sản phẩm:</strong> {{ $review->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                    <div class="mb-3"><strong>Đánh giá:</strong> {{ $review->rating }} ⭐</div>
                    <div class="mb-3"><strong>Bình luận:</strong> {{ $review->comment }}</div>
                    <div class="mb-3"><strong>Ngày tạo:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong>
                        <span class="ms-2 badge {{ $review->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $review->status_label }}
                        </span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection