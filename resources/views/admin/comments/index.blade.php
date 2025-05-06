@php use Illuminate\Support\Str; @endphp
@extends('admin.layout')

@section('title', 'Bình luận')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center p-3 rounded shadow-sm">
                <h4 class="page-title mb-0 fw-bold"><i class="la la-comments me-2"></i>Quản Lý Bình Luận</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">StepViet</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Bình luận</li>
                </ol>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm rounded-lg mb-3">
        <div class="card-body">
            <form action="{{ route('binh-luan.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-box me-1"></i> Sản phẩm</label>
                        <select name="product_id" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-eye me-1"></i> Hiển thị</label>
                        <select name="is_hidden" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="">-- Tất cả --</option>
                            <option value="1" {{ request('is_hidden') === '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('is_hidden') === '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="fas fa-search me-1"></i> Tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-sm border-0 shadow-sm" placeholder="Tìm theo nội dung bình luận" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-12 d-flex gap-2 justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('binh-luan.index') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-sync me-1"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="CommentTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3">ID</th>
                            <th class="text-center py-3">Sản phẩm</th>
                            <th class="text-center py-3">Người dùng</th>
                            <th class="text-center py-3">Nội dung</th>
                            <th class="text-center py-3">Hiển thị</th>
                            <th class="text-center py-3">Ngày tạo</th>
                            <th class="text-center py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                        <tr>
                            <td class="text-center">{{ $comment->id }}</td>
                            <td>{{ $comment->product->name ?? 'N/A' }}</td>
                            <td>{{ $comment->user->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($comment->message, 50) }}</td>
                            <td class="text-center">
                                <form action="{{ route('binh-luan.update', $comment->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        <option value="1" {{ $comment->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="0" {{ $comment->status == 0 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#showCommentModal{{ $comment->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không có bình luận nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem Bình luận -->
@foreach ($comments as $comment)
<div class="modal fade" id="showCommentModal{{ $comment->id }}" tabindex="-1"
    aria-labelledby="showCommentModalLabel{{ $comment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-sm">
            {{-- Modal Header --}}
            <div class="modal-header d-flex justify-content-between align-items-center bg-light border-bottom-0 px-4 py-3">
                <h5 class="modal-title fw-bold text-primary mb-0" id="showCommentModalLabel{{ $comment->id }}">
                    <i class="fas fa-comment-dots me-2 text-info"></i>Chi tiết bình luận #{{ $comment->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
    
            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item py-2">
                        <strong>Sản phẩm:</strong> {{ $comment->product->name ?? 'Không xác định' }}
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Người dùng:</strong> {{ $comment->user->name ?? 'Không xác định' }}
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Nội dung:</strong>
                        <p class="mt-1 mb-0 text-muted fst-italic">{{ $comment->message }}</p>
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Bình luận cha:</strong> {{ $comment->parent ? ('#' . $comment->parent->id) : 'Không có' }}
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Trạng thái hiển thị:</strong>
                        <span class="ms-2 badge {{ $comment->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $comment->status_label }}
                        </span>
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Thời gian tạo:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endforeach


</div>

@endsection