@extends('layouts.app')

@section('content')

      <h1>{{ $posts->title }} </h1>
      <h5> {!! $posts->body !!} </h5>
      <small>{{ $posts->created_at }}</small>
      {{-- Use this and remove pull-right class if you want the buttons to appear in 1 line
      <form class="input-inline"> </form> --}}
      <h6> Are you sure you want to delete this post ?? </h6>
      <div class="form-inline">
        @auth
          @if(Auth::user()->id == $posts->user_id)
            <form type="hidden" style="margin-right: 5px" action="{{ route('posts.destroy', $posts->id) }}" method="post">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger"> Yes </button>
            </form>
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('posts.show', $posts->id) }}"> No </a>
          @endif
        @endauth
      </div>
      <br>
      @if($posts->cover_image != 'noimage.jpg')
      <div class="col-md-6">
        <img src="/storage/cover_images/{{$posts->cover_image}}" class="w-100">
      </div>
      @endif
      <br><br>

@endsection
