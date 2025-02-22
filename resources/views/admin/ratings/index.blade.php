@extends('admin.layout')
@section('titlepage', 'Danh sách đánh giá')
@section('content')

    <div class="container">
        <h2>Đánh giá</h2>

        @foreach($ratings as $rating)
            <div class="border p-3 mb-3">
                <strong>{{ $rating->user->name }}</strong>
                <p>
                    <span class="text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $rating->stars ? '' : '-o' }}"></i>
                        @endfor
                    </span>
                </p>
                <small>{{ $rating->created_at->diffForHumans() }}</small>

                @if(auth()->id() == $rating->user_id)
                    <form method="POST" action="{{ route('ratings.destroy', $rating->id) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                @endif
            </div>
        @endforeach

        {{ $ratings->links() }}

        <h3>Thêm đánh giá</h3>
        <form method="POST" action="{{ route('ratings.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ request()->route('product_id') }}">

            <div class="mb-3">
                <label class="form-label">Chọn sao:</label>
                <select name="stars" class="form-select" required>
                    <option value="1">⭐ 1</option>
                    <option value="2">⭐⭐ 2</option>
                    <option value="3">⭐⭐⭐ 3</option>
                    <option value="4">⭐⭐⭐⭐ 4</option>
                    <option value="5">⭐⭐⭐⭐⭐ 5</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
    </div>


@endsection