@extends('admin.layout')
@section('title', 'Quản lý Size giày')
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4 class="m-0 font-weight-bold text-primary">Quản lý Size giày</h4>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSizeModal">
                <i class="fas fa-plus"></i> Thêm Size
            </button>
        </div>
        <div class="card-body">
            <!-- Form thêm size (Modal) -->
            <div class="modal fade" id="addSizeModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm Size mới</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.sizes.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="size_name">Tên size</label>
                                    <input type="number" class="form-control" id="size_name" name="name" 
                                           min="35" max="45" step="0.5" required>
                                    <small class="form-text text-muted">Ví dụ: 38, 39.5, 40</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Thêm Size</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bảng danh sách size -->
            <div class="table-responsive">
                <table id="SizeTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">STT</th>
                            <th>Size</th>
                            <th width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $index => $size)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $size->name }}</td>
                            <td class="text-center">
                                <!-- Nút sửa -->
                                <button class="btn btn-sm btn-warning edit-size" 
                                        data-id="{{ $size->id }}"
                                        data-name="{{ $size->name }}"
                                        data-toggle="modal" 
                                        data-target="#editSizeModal">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                
                                <!-- Nút xóa -->
                                <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Bạn có chắc muốn xóa size này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-3">
                {{ $sizes->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa -->
<div class="modal fade" id="editSizeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa Size</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSizeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_size_name">Tên size</label>
                        <input type="number" class="form-control" id="edit_size_name" name="name" 
                               min="35" max="45" step="0.5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Xử lý hiển thị form edit
        $('.edit-size').click(function() {
            var sizeId = $(this).data('id');
            var sizeName = $(this).data('name');
            
            $('#edit_size_name').val(sizeName);
            $('#editSizeForm').attr('action', '/admin/sizes/' + sizeId);
        });
    });
</script>
@endpush