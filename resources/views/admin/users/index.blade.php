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
        <div class="col-md-8">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body p-4">
                    <form action="{{ route('nguoi-dung.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="fas fa-user-tag me-1"></i> Vai
                                    trò</label>
                                <select name="role" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Tất cả --</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="fas fa-map-marker-alt me-1"></i> Địa
                                    chỉ</label>
                                <input type="text" name="address"
                                    class="form-control form-control-sm border-0 shadow-sm" placeholder="Nhập địa chỉ"
                                    value="{{ request('address') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><i class="fas fa-sort me-1"></i> Sắp xếp</label>
                                <select name="sort_by" class="form-select form-select-sm border-0 shadow-sm">
                                    <option value="">-- Mặc định --</option>
                                    <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên
                                        A-Z</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                        Tên Z-A</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('san-pham.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                                <i class="fas fa-sync me-1"></i> Làm mới
                            </a>
                            <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-1"></i> Thêm người dùng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm người dùng -->
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addUserModalLabel"><i class="fas fa-user-plus me-2"></i> Thêm
                        người dùng mới</h5>
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
                    <form action="{{ route('nguoi-dung.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Tên</label>
                                <input type="text" name="name" id="name" class="form-control border-0 shadow-sm"
                                    value="{{ old('name') }}" placeholder="Nhập tên" required>
                                @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email" class="form-control border-0 shadow-sm"
                                    value="{{ old('email') }}" placeholder="Nhập email" required>
                                @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                <input type="password" name="password" id="password"
                                    class="form-control border-0 shadow-sm" placeholder="Nhập mật khẩu" required>
                                @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">Vai trò</label>
                                <select name="role" id="role" class="form-select border-0 shadow-sm">
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label fw-semibold">Địa chỉ</label>
                                <input type="text" name="address" id="address" class="form-control border-0 shadow-sm"
                                    value="{{ old('address') }}" placeholder="Nhập địa chỉ">
                                @error('address')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                                <div class="input-group">
                                    <input type="file" name="avatar" id="avatar" class="form-control border-0 shadow-sm"
                                        accept="image/*" data-preview="preview_add">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="document.getElementById('avatar').click()">Chọn file</button>
                                </div>
                                <div class="image-preview mt-3" id="preview_add">
                                    <img id="preview_add_img" src="" alt="Ảnh xem trước"
                                        class="rounded shadow-sm d-none"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                @error('avatar')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-secondary btn-sm fw-semibold"
                                data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Lưu người dùng</button>
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
                                    <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $user->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
                                                    <div
                                                        class="col-md-4 d-flex justify-content-center align-items-center">
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

                                <!-- Modal chỉnh sửa -->
                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content rounded-lg shadow-lg">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold" id="editModalLabel{{ $user->id }}">
                                                    <i class="fas fa-edit me-2"></i> Chỉnh sửa người dùng
                                                    #{{ $user->id }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                                @endif
                                                <form action="{{ route('nguoi-dung.update', $user->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-4">
                                                        <div class="col-md-6">
                                                            <label for="name_{{ $user->id }}"
                                                                class="form-label fw-semibold">Tên</label>
                                                            <input type="text" name="name" id="name_{{ $user->id }}"
                                                                class="form-control border-0 shadow-sm"
                                                                value="{{ old('name', $user->name) }}"
                                                                placeholder="Nhập tên" required>
                                                            @error('name')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="email_{{ $user->id }}"
                                                                class="form-label fw-semibold">Email</label>
                                                            <input type="email" name="email" id="email_{{ $user->id }}"
                                                                class="form-control border-0 shadow-sm"
                                                                value="{{ old('email', $user->email) }}"
                                                                placeholder="Nhập email" required>
                                                            @error('email')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="role_{{ $user->id }}"
                                                                class="form-label fw-semibold">Vai trò</label>
                                                            <select name="role" id="role_{{ $user->id }}"
                                                                class="form-select border-0 shadow-sm">
                                                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                                                    Người dùng
                                                                </option>
                                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                                    Admin
                                                                </option>
                                                            </select>
                                                            @error('role')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="address_{{ $user->id }}"
                                                                class="form-label fw-semibold">Địa chỉ</label>
                                                            <input type="text" name="address"
                                                                id="address_{{ $user->id }}"
                                                                class="form-control border-0 shadow-sm"
                                                                value="{{ old('address', $user->address) }}"
                                                                placeholder="Nhập địa chỉ">
                                                            @error('address')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="avatar_{{ $user->id }}"
                                                                class="form-label fw-semibold">Ảnh đại diện</label>
                                                            <div class="input-group">
                                                                <input type="file" name="avatar"
                                                                    id="avatar_{{ $user->id }}"
                                                                    class="form-control border-0 shadow-sm"
                                                                    accept="image/*"
                                                                    data-preview="preview_{{ $user->id }}">
                                                                <button type="button" class="btn btn-outline-primary"
                                                                    onclick="document.getElementById('avatar_{{ $user->id }}').click()">
                                                                    Chọn file
                                                                </button>
                                                            </div>
                                                            <div class="mt-3 d-flex align-items-center gap-3">
                                                                @if (!empty($user->avatar))
                                                                <div class="current-image">
                                                                    <label class="form-label small text-muted">Ảnh hiện
                                                                        tại:</label>
                                                                    <img src="{{ asset($user->avatar) }}"
                                                                        alt="Ảnh hiện tại" class="rounded shadow-sm"
                                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                                </div>
                                                                @endif
                                                                <div class="preview-image">
                                                                    <label class="form-label small text-muted">Ảnh xem
                                                                        trước:</label>
                                                                    <img id="preview_{{ $user->id }}" src=""
                                                                        alt="Ảnh xem trước"
                                                                        class="rounded shadow-sm d-none"
                                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                                </div>
                                                            </div>
                                                            @error('avatar')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-4">
                                                        <button type="button"
                                                            class="btn btn-secondary btn-sm fw-semibold"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm fw-semibold">Cập nhật</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
</div>
@endsection