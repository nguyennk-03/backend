@extends('admin.layout')
@section('title', 'Danh mục')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-tags me-2"></i>Quản Lý Danh Mục</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Danh mục</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Thông báo thành công -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Bộ lọc và nút hành động -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body p-4">
                    <!-- Form để lọc danh mục theo sắp xếp -->
                    <form action="{{ route('danh-muc.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <!-- Sắp xếp danh mục -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="fas fa-sort me-1"></i> Sắp xếp</label>
                                <select name="sort_by" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Mặc định --</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                </select>
                            </div>
                            <!-- Các nút hành động -->
                            <div class="col-md-6 d-flex gap-2 align-items-end">
                                <!-- Nút gửi form lọc -->
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                                <!-- Nút làm mới bộ lọc -->
                                <a href="{{ route('danh-muc.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-sync me-1"></i> Làm mới
                                </a>
                                <!-- Nút mở modal thêm danh mục -->
                                <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus me-1"></i> Thêm danh mục
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm danh mục -->
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="categoryModalLabel"><i class="fas fa-plus-circle me-2"></i> Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Hiển thị lỗi xác thực nếu có -->
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
                    <!-- Form thêm danh mục mới -->
                    <form action="{{ route('danh-muc.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Tên danh mục -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Tên danh mục</label>
                                <input type="text" name="name" id="name" class="form-control border-0 shadow-sm" value="{{ old('name') }}" placeholder="Nhập tên danh mục" required>
                                @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Trạng thái hiển thị -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" id="status" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Hình ảnh -->
                            <div class="col-md-12">
                                <label for="image" class="form-label fw-semibold">Chọn hình ảnh</label>
                                <div class="input-group">
                                    <input type="file" name="image" id="image" class="form-control border-0 shadow-sm" accept="image/*" data-preview="preview_add">
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image').click()">Chọn file</button>
                                </div>
                                <div class="image-preview mt-3" id="preview_add">
                                    <img id="preview_add_img" src="" alt="Ảnh xem trước" class="rounded shadow-sm d-none" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                @error('image')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu danh mục</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh mục -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <!-- Bảng hiển thị danh sách danh mục -->
                <table id="CategoryTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Hình ảnh</th>
                            <th class="text-center py-3">Tên</th>
                            <th class="text-center py-3">Trạng thái</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Lặp qua danh sách danh mục -->
                        @forelse ($categories as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <!-- Hiển thị hình ảnh hoặc thông báo nếu không có -->
                            <td class="text-center">
                                @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail rounded" style="object-fit: cover; max-width: 100px; max-height: 100px;" alt="{{ $item->name }}">
                                @else
                                <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <!-- Hiển thị tên danh mục -->
                            <td>{{ $item->name }}</td>
                            <!-- Hiển thị trạng thái -->
                            <td class="text-center">{{ $item->status ? 'Hiển thị' : 'Ẩn' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Nút chỉnh sửa danh mục -->
                                    <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Form xóa danh mục -->
                                    <form action="{{ route('danh-muc.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                        @empty
                        <!-- Hiển thị thông báo nếu không có danh mục -->
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Không có danh mục nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa -->
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}"><i class="fas fa-edit me-2"></i> Chỉnh sửa danh mục #{{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Hiển thị lỗi xác thực nếu có -->
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <!-- Form chỉnh sửa danh mục -->
                    <form action="{{ route('danh-muc.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Tên danh mục -->
                            <div class="col-md-6">
                                <label for="name_{{ $item->id }}" class="form-label fw-semibold">Tên danh mục</label>
                                <input type="text" name="name" id="name_{{ $item->id }}" class="form-control border-0 shadow-sm" value="{{ old('name', $item->name) }}" placeholder="Nhập tên danh mục" required>
                                @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Trạng thái hiển thị -->
                            <div class="col-md-6">
                                <label for="status_{{ $item->id }}" class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" id="status_{{ $item->id }}" class="form-select border-0 shadow-sm" required>
                                    <option value="1" {{ old('status', $item->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status', $item->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Hình ảnh -->
                            <div class="col-md-12">
                                <label for="image_{{ $item->id }}" class="form-label fw-semibold">Chọn hình ảnh</label>
                                <div class="input-group">
                                    <input type="file" name="image" id="image_{{ $item->id }}" class="form-control border-0 shadow-sm" accept="image/*" data-preview="preview_{{ $item->id }}">
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image_{{ $item->id }}').click()">Chọn file</button>
                                </div>
                                <div class="mt-3 d-flex align-items-center gap-3">
                                    @if ($item->image)
                                    <div class="current-image">
                                        <label class="form-label small text-muted">Hình ảnh hiện tại:</label>
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Hình ảnh hiện tại" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                    @endif
                                    <div class="preview-image">
                                        <label class="form-label small text-muted">Hình ảnh xem trước:</label>
                                        <img id="preview_{{ $item->id }}" src="" alt="Ảnh xem trước" class="rounded shadow-sm d-none" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                </div>
                                @error('image')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection