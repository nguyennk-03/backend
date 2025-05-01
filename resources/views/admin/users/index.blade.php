@extends('admin.layout')
@section('title', 'Người dùng')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-users me-2"></i>Quản Lý Người Dùng</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Người dùng</li>
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
                    <form action="{{ route('nguoi-dung.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-user-tag me-1"></i> Vai trò</label>
                                <select name="role" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Tất cả --</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-sort me-1"></i> Sắp xếp</label>
                                <select name="sort_by" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Mặc định --</option>
                                    <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2 align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('nguoi-dung.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                                    <i class="fas fa-sync me-1"></i> Làm mới
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng người dùng -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="UserTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Ảnh đại diện</th>
                            <th class="text-center py-3">Tên</th>
                            <th class="text-center py-3">Email</th>
                            <th class="text-center py-3">Vai trò</th>
                            <th class="text-center py-3">Địa chỉ</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td class="text-center">
                                @if (!empty($user->avatar))
                                <img src="{{ asset($user->avatar) }}" class="img-thumbnail rounded"
                                    style="object-fit: cover; max-width: 100px; max-height: 100px;"
                                    alt="{{ $user->name }}">
                                @else
                                <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->role }}</td>
                            <td>{{ $user->address ?? 'Chưa có địa chỉ' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $user->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có người dùng nào để hiển thị.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal xem chi tiết -->
    <div class="modal fade" id="showModal{{ $user->id }}" tabindex="-1"
        aria-labelledby="showModalLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="showModalLabel{{ $user->id }}">
                        <i class="fas fa-info-circle me-2"></i> Chi tiết người dùng
                        #{{ $user->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                            @if (!empty($user->avatar))
                            <img src="{{ asset($user->avatar) }}"
                                class="img-fluid rounded shadow-sm" alt="{{ $user->name }}">
                            @else
                            <div class="bg-light rounded p-3 text-muted text-center"
                                style="width: 200px; height: 200px; line-height: 200px;">
                                Chưa có ảnh
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 p-3 rounded shadow-sm">
                                <p class="mb-2"><strong>Tên:</strong> {{ $user->name }}</p>
                                <p class="mb-2"><strong>Email:</strong> {{ $user->email }}
                                </p>
                                <p class="mb-2"><strong>Vai trò:</strong> {{ $user->role }}
                                </p>
                                <p class="mb-2"><strong>Địa chỉ:</strong>
                                    {{ $user->address ?? 'Chưa có địa chỉ' }}
                                </p>
                                <p class="mb-0"><strong>Ngày tạo:</strong>
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold"
                        data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection