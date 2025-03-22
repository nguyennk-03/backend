@extends('admin.layout')
@section('title', 'Danh sách người dùng')
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm rounded-lg mb-3">
            <div class="card-body">
                <form action="{{ route('nguoi-dung.index') }}" method="GET">
                    <div class="row g-3">
                        <!-- Bộ lọc theo vai trò -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Vai trò</label>
                            <select name="role" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên
                                </option>
                            </select>
                        </div>

                        <!-- Nút tìm kiếm & làm mới -->
                        <div class="col-md-4 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('nguoi-dung.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                        </div>

                        <div class="col-md-4 d-flex justify-content-end align-items-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addUserModal">
                                <i class="fas fa-plus"></i> Thêm người dùng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <h4 class="header-title mb-3">Danh Sách Người Dùng</h4>
                <table id="UserTable" class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
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
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('nguoi-dung.show', $user->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('nguoi-dung.edit', $user->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('nguoi-dung.destroy', $user->id) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection