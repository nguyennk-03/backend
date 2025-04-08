@extends('admin.layout')
@section('title', 'Quản lý màu sắc')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quản lý màu sắc</h5>
                </div>

                <div class="card-body">
                    <!-- Form thêm màu -->
                    <div class="mb-4 p-4 border rounded">
                        <h5 class="mb-3">Thêm Màu</h5>
                        <form action="{{ route('colors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên màu</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="image" class="form-label">Ảnh màu</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <button class="btn btn-outline-secondary" type="button" id="clearFile">Xóa</button>
                                    </div>
                                    <div class="form-text">Không có tệp nào được chọn</div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Thêm màu</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bảng danh sách màu -->
                    <div class="mt-4">
                        <h5 class="mb-3">Bảng Màu</h5>
                        <div class="table-responsive">
                            <table id="ColorTable" class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th>Tên màu</th>
                                        <th width="10%">Ảnh</th>
                                        <th width="15%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($colors as $index => $color)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $color->name }}</td>
                                        <td class="text-center">
                                            @if($color->image)
                                            <span class="text-success">✅</span>
                                            @else
                                            <span class="text-secondary">❌</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('colors.destroy', $color->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa màu này?')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('clearFile').addEventListener('click', function() {
        document.getElementById('image').value = '';
        document.querySelector('.form-text').textContent = 'Không có tệp nào được chọn';
    });

    document.getElementById('image').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Không có tệp nào được chọn';
        document.querySelector('.form-text').textContent = fileName;
    });
</script>
@endpush