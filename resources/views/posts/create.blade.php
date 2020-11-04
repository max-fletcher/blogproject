@extends('layouts.app')

@section('content')
  @if( auth()->user()->locked == 'unlocked' )
  <h1> Create Posts </h1>
    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" value="{{ old('title') }}">
      </div>
      <div class="form-group">
        <label for="body">Body</label>
        <textarea class="form-control" rows="7" name="body" id="body" placeholder="Enter Post Body">{{ old('body') }}</textarea>
      </div>
      <div class="input-group">
          <div class="custom-file col-sm-3">
              <input type="file" class="custom-file-input" name="cover_image" id="cover_image">
              <label class="custom-file-label" for="cover_image">Choose file</label>
          </div>
      </div>
      <br>
      <input type="submit" class="btn btn-primary" value="Submit">
    </form>
  @elseif( auth()->user()->locked == 'unlocked' )
    <h2 class="text-center"> You account is currently locked for failing to follow our community standards and receiving excessive reports or violating our rules. </h2>
    <h2 class="text-center"> Contact out admins for furthur inquiry. </h2>
  @endif
@endsection
