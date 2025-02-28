@extends('admin.layout')
@section('titlepage', 'Quản lý Đánh Giá')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Quản lý Đánh Giá</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Đánh Giá</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tìm kiếm & Lọc trạng thái -->
        <div class="card shadow-sm rounded-lg mb-3">
            <div class="card-body">
                <form action="{{ route('reviews') }}" method="GET">
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('reviews') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                        </div>

                        <!-- Nút Thêm Đánh Giá -->
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addReviewModal">
                                <i class="fas fa-plus"></i> Thêm đánh giá
                            </button>
                        </div>
                        <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addReviewModalLabel">Thêm Đánh Giá</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('reviewAdd') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="product_id" class="form-label fw-bold">Sản phẩm</label>
                                                <select name="product_id" id="product_id" class="form-select" required>
                                                    <option value="">-- Chọn sản phẩm --</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Đánh giá</label>
                                                <select name="rating" class="form-select" required>
                                                    <option value="">-- Chọn số sao --</option>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="comment" class="form-label fw-bold">Bình luận</label>
                                                <textarea name="comment" id="comment" class="form-control" rows="3"
                                                    placeholder="Nhập bình luận..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-primary">Lưu đánh giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách Đánh Giá -->
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
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td class="text-center">{{ $review->id }}</td>
                                <td>{{ $review->user ? $review->user->full_name : 'Ẩn danh' }}</td>
                                <td>{{ $review->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                <td class="text-center">{{ $review->rating }} ⭐</td>
                                <td>{{ $review->comment }}</td>
                                <td class="text-center">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('reviewEdit', $review->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reviewDelete', $review->id) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có đánh giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center"
                    style="background-color: #343a40; color: #fff;">
                    <div>
                        Hiển thị <strong>{{ $reviews->firstItem() }}</strong> đến
                        <strong>{{ $reviews->lastItem() }}</strong> trong tổng số
                        <strong>{{ $reviews->total() }}</strong> đánh giá
                    </div>
                    <div class="pagination-container">
                        {{ $reviews->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection