@extends('layouts.app')

@section('content')

      <h5>Comment ID: {{ $comment->id }} </h5>
      <h4> {!! $comment->comment !!} </h4>
      <small> {{ $comment->created_at }} by <a style="font-size: 14px; padding-left: 2px" href="{{ route('profile.display',$comment->user_id ) }}"> {{ $comment_user->name}} </a> </small>
      {{-- Use this and remove pull-right class if you want the buttons to appear in 1 line
      <form class="input-inline"> </form> --}}
      <h6> Are you sure you want to delete this comment ?? </h6>
      <div class="form-inline">
        @auth
          @if(Auth::user()->id == $comment->user_id)
            <form type="hidden" style="margin-right: 5px" action="{{ route('comments.destroy', $comment->id) }}" method="post">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger"> Yes </button>
            </form>
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('posts.show', $comment->post_id) }}"> No </a>
          @endif
        @endauth
      </div>

@endsection
