@extends('admin.layout')
@section('title', 'Danh mục')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Danh Mục</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Danh mục</li>
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm rounded-lg mb-4 p-3 border-0 bg-light">
            <div class="card-body">
                <form action="{{ route('danh-muc.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <!-- Có thể thêm bộ lọc nếu cần, nhưng hiện tại giữ đơn giản -->
                        </div>
                        <div class="col-md-4 d-flex justify-content-end gap-3">
                            <a href="{{ route('danh-muc.index') }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
                            <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus"></i> Thêm danh mục
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="categoryModalLabel">Thêm danh mục mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('danh-muc.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên danh mục</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Nhập tên danh mục" required>
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                        placeholder="Nhập slug" required>
                                    @error('slug')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="img_url" class="form-label">Hình ảnh</label>
                                    <input type="file" name="img_url" id="img_url" class="form-control" accept="image/*">
                                    <div class="image-preview mt-2" id="preview_add"></div>
                                    @error('img_url')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary btn-sm">Lưu danh mục</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="CategoryTable" class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Hình</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Slug</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $item)
                                <tr>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-center">
                                        @if (!empty($item->image))
                                            <img src="{{ $item->image }}" class="img-thumbnail"
                                                style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $item->name }}">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start gap-2">
                                            <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                                data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('danh-muc.destroy', $item->id) }}" method="POST"
                                                class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm shadow-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="showModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="showModalLabel{{ $item->id }}">
                                                            Chi tiết danh mục #{{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-4">
                                                            <div
                                                                class="col-md-4 d-flex justify-content-center align-items-center">
                                                                @if (!empty($item->image))
                                                                    <img src="{{ $item->image }}" class="img-fluid"
                                                                        alt="{{ $item->name }}">
                                                                @else
                                                                    <div class="bg-light rounded p-3 text-muted text-center"
                                                                        style="width: 200px; height: 200px; line-height: 200px;">
                                                                        Chưa có ảnh</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card border-0 p-3">
                                                                    <p class="mb-2"><strong>Tên:</strong> {{ $item->name }}</p>
                                                                    <p class="mb-2"><strong>Slug:</strong> {{ $item->slug }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}">
                                                            Chỉnh sửa danh mục #{{ $item->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('danh-muc.update', $item->id) }}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row g-4">
                                                                <div class="col-md-6">
                                                                    <label for="name_{{ $item->id }}" class="form-label">Tên
                                                                        danh mục</label>
                                                                    <input type="text" name="name" id="name_{{ $item->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('name', $item->name) }}"
                                                                        placeholder="Nhập tên danh mục" required>
                                                                    @error('name')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="slug_{{ $item->id }}"
                                                                        class="form-label">Slug</label>
                                                                    <input type="text" name="slug" id="slug_{{ $item->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('slug', $item->slug) }}"
                                                                        placeholder="Nhập slug" required>
                                                                    @error('slug')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="img_url_{{ $item->id }}" class="form-label">Hình
                                                                        ảnh</label>
                                                                    <input type="file" name="img_url"
                                                                        id="img_url_{{ $item->id }}" class="form-control"
                                                                        accept="image/*">
                                                                    <div class="image-preview mt-2"
                                                                        id="preview_{{ $item->id }}">
                                                                        @if ($item->image_display_url)
                                                                            <img src="{{ $item->image_display_url }}"
                                                                                class="img-thumbnail" alt="{{ $item->name }}">
                                                                        @endif
                                                                    </div>
                                                                    @error('img_url')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary btn-sm">Cập
                                                                    nhật</button>
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