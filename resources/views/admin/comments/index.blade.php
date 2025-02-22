@extends('admin.layout')
@section('titlepage', 'Danh sách bình luận')
@section('content')

<div class="container">
    <h2>Bình luận</h2>

    @foreach($comments as $comment)
        <div class="border p-3 mb-3">
            <strong>{{ $comment->user->name }}</strong>
            <p>{{ $comment->content }}</p>
            <small>{{ $comment->created_at->diffForHumans() }}</small>

            @if(auth()->id() == $comment->user_id)
                <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            @endif
        </div>
    @endforeach

    {{ $comments->links() }}

    <h3>Thêm bình luận</h3>
    <form method="POST" action="{{ route('comments.store') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ request()->route('product_id') }}">
        <div class="mb-3">
            <label class="form-label">Bình luận:</label>
            <textarea name="content" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi</button>
    </form>
</div>

@endsection