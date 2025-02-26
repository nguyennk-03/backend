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
                    <!-- Modal thêm người dùng -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserLabel">Thêm Người Dùng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addUserForm" action="{{ route('userAdd') }}" method="POST" autocomplete="off">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Họ và Tên</label>
                                            <input type="text" id="name" name="name" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <input type="text" id="phone" name="phone" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Vai trò</label>
                                            <select name="role" id="role" class="form-select">
                                                <option value="user">Người dùng</option>
                                                <option value="admin">Quản trị viên</option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-primary ms-2">Lưu</button>
                                        </div>
                                    </form>
                                </div>
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
                                        Sửa
                                    </button>
                                    <a href="{{ route('userDelete', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa người dùng -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">Chỉnh sửa Người Dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="modal-body">
                    <form action="{{ route('userUpdate', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_user_id" name="id">   
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Họ và Tên</label>
                            <input type="text" id="edit_name" name="name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Số điện thoại</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Vai trò</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="user">Người dùng</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary ms-2">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection