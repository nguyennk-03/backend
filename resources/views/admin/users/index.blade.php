@extends('admin.layout')
@section('title', 'Người dùng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Người Dùng</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Người dùng</li>
                    </ol>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm rounded-lg mb-4 p-3 border-0 bg-light">
            <div class="card-body">
                <form action="{{ route('nguoi-dung.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary">Vai trò</label>
                                    <select name="role" class="form-select form-select-sm border-primary">
                                        <option value="">-- Tất cả --</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                    </select>
                                </div>
                                <div class="col-md-3 position-relative">
                                    <label class="form-label fw-bold text-primary">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control form-control-sm border-primary"
                                        placeholder="Địa chỉ" value="{{ request('address') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary">Sắp xếp</label>
                                    <select name="sort_by" class="form-select form-select-sm border-primary">
                                        <option value="">-- Mặc định --</option>
                                        <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>
                                            Mới nhất</option>
                                        <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Cũ
                                            nhất</option>
                                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('nguoi-dung.index') }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                            <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addUserModal">
                                <i class="fas fa-plus"></i> Thêm người dùng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="addUserModalLabel">Thêm người dùng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('nguoi-dung.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nhập tên" required>
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Nhập email" required>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Vai trò</label>
                                    <select name="role" id="role" class="form-select">
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Nhập địa chỉ">
                                    @error('address')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-file-upload">
                                        <label for="avatar" class="form-label">Chọn ảnh đại diện</label>
                                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" style="display: none;">
                                    </div>
                                    <div class="image-preview mt-2" id="preview_add"></div>
                                    @error('avatar')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary btn-sm">Lưu người dùng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- nguoi-dung Table -->
        <div class="card shadow-sm rounded">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="UserTable" class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Ảnh đại diện</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center">Địa chỉ</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->id }}</td>
                                    <td class="text-center">
                                        @if (!empty($user->avatar))
                                            <img src="{{ asset($user->avatar) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $user->name }}">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->role }}</td>
                                    <td>{{ $user->address ?? 'Chưa có địa chỉ' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start gap-2">
                                            <button type="button" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#showModal{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('nguoi-dung.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Show Modal -->
                                        <div class="modal fade" id="showModal{{ $user->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="showModalLabel{{ $user->id }}">Chi tiết người dùng #{{ $user->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-4">
                                                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                                                @if (!empty($user->avatar))
                                                                    <img src="{{ asset($user->avatar) }}" class="img-fluid" alt="{{ $user->name }}">
                                                                @else
                                                                    <div class="bg-light rounded p-3 text-muted text-center" style="width: 200px; height: 200px; line-height: 200px;">Chưa có ảnh</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card border-0 p-3">
                                                                    <p class="mb-2"><strong>Tên:</strong> {{ $user->name }}</p>
                                                                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                                                                    <p class="mb-2"><strong>Vai trò:</strong> {{ $user->role }}</p>
                                                                    <p class="mb-2"><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa có địa chỉ' }}</p>
                                                                    <p class="mb-0"><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="editModalLabel{{ $user->id }}">Chỉnh sửa người dùng #{{ $user->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('nguoi-dung.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row g-4">
                                                                <div class="col-md-6">
                                                                    <label for="name_{{ $user->id }}" class="form-label">Tên</label>
                                                                    <input type="text" name="name" id="name_{{ $user->id }}" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Nhập tên" required>
                                                                    @error('name')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="email_{{ $user->id }}" class="form-label">Email</label>
                                                                    <input type="email" name="email" id="email_{{ $user->id }}" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Nhập email" required>
                                                                    @error('email')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="role_{{ $user->id }}" class="form-label">Vai trò</label>
                                                                    <select name="role" id="role_{{ $user->id }}" class="form-select">
                                                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Người dùng</option>
                                                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                    </select>
                                                                    @error('role')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="address_{{ $user->id }}" class="form-label">Địa chỉ</label>
                                                                    <input type="text" name="address" id="address_{{ $user->id }}" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">
                                                                    @error('address')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-file-upload">
                                                                        <label for="avatar_{{ $user->id }}" class="form-label">Chọn ảnh đại diện</label>
                                                                        <input type="file" name="avatar" id="avatar_{{ $user->id }}" class="form-control" accept="image/*" style="display: none;">
                                                                    </div>
                                                                    <div class="image-preview mt-2" id="preview_{{ $user->id }}">
                                                                        @if ($user->avatar)
                                                                            <img src="{{ asset($user->avatar) }}" class="img-thumbnail" alt="{{ $user->name }}">
                                                                        @endif
                                                                    </div>
                                                                    @error('avatar')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection