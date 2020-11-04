@extends('layouts.app')

@section('content')
  <h1> Edit Comment </h1>
    <form action="{{ route('comments.update', $comment->id) }}" method="post" enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <label for="comment">Comment</label>
      <textarea class="form-control" rows="7" name="comment" id="comment" placeholder="Enter Comment">{{ $comment->comment }}</textarea>
      <br>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
