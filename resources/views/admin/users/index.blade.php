@extends('admin.layout')
@section('titlepage', 'Danh sách người dùng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Danh Sách Người Dùng</h4>
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

        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Danh Sách Người Dùng</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus-circle"></i> Thêm người dùng
                        </button>
                    </div>
                    <!-- Modal Thêm Người Dùng -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Thêm Người Dùng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-user"></i> Họ &
                                                    Tên</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-envelope"></i>
                                                    Email</label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-phone"></i> Số điện
                                                    thoại</label>
                                                <input type="text" name="phone" class="form-control">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-user-tag"></i> Vai
                                                    trò</label>
                                                <select name="role" class="form-select">
                                                    <option value="user">Người dùng</option>
                                                    <option value="admin">Quản trị viên</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-image"></i> Ảnh đại
                                                    diện</label>
                                                <input type="file" name="avatar" class="form-control">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-lock"></i> Mật
                                                    khẩu</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold"><i class="fas fa-lock"></i> Xác nhận mật
                                                    khẩu</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                                class="fas fa-times"></i> Đóng</button>
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Thêm Người
                                            Dùng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bảng danh sách người dùng -->
                <table id="UserTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ và Tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-user-btn" data-id="{{ $user->id }}"
                                        data-name="{{ $user->full_name }}" data-email="{{ $user->email }}"
                                        data-phone="{{ $user->phone }}" data-role="{{ $user->role }}" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal">
                                        <i class="fas fa-edit">Sửa</i>
                                    </button>
                                    <a href="{{ route('userDelete', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"><i class="fas fa-trash-alt">Xóa</i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-edit"></i> Cập Nhật Người Dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <input type="hidden" id="userId" name="id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold"><i class="fas fa-user"></i> Họ & Tên</label>
                                <input type="text" name="name" class="form-control" id="editName" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" name="email" class="form-control" id="editEmail" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold"><i class="fas fa-phone"></i> Số điện
                                    thoại</label>
                                <input type="text" name="phone" class="form-control" id="editPhone">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-user-tag"></i> Vai trò</label>
                                <select name="role" class="form-select" id="editRole">
                                    <option value="user">Người dùng</option>
                                    <option value="admin">Quản trị viên</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-image"></i> Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control">
                                <div class="mt-2">
                                    <img id="previewAvatar" src="" alt="Avatar" class="img-thumbnail" width="100">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold"><i class="fas fa-lock"></i> Mật khẩu (Để
                                    trống nếu không đổi)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>
                            Đóng</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript xử lý Modal -->
    <script>

    </script>

@endsection