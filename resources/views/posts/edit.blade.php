@extends('layouts.app')

@section('content')
  <h1> Create Posts </h1>
    <form action="{{ route('posts.update', $posts->id) }}" method="post" enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" value="{{ $posts->title }}">
      </div>
      <label for="body">Body</label>
      <textarea class="form-control" rows="7" name="body" id="body" placeholder="Enter Post Body">{{ $posts->body }}</textarea>
      <br>
      <div class="form-group">
      <input type="file" name="cover_image" id="cover_image">
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
