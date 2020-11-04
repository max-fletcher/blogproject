@extends('layouts.app')

@section('content')
  @if($posts->flagged == '0' || ($posts->flagged == '1' && ( auth()->user()->id == $posts->user_id || auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')))
      <h1> {{ $posts->title }} @if($posts->flagged == '1') <span class="badge badge-danger"> Flagged !! </span> @endif </h1>
      <h5> {!! $posts->body !!} </h5>
      <small>Written on: <a style="padding-right: 2px;"> {{ $posts->created_at }} </a>  by <a style="font-size: 14px; padding-left: 2px" href="{{ route('profile.display',$posts->user_id ) }}"> {{$posts->user->name}} </a> </small>
      {{-- Use this and remove pull-right class if you want the buttons to appear in 1 line
      <form class="input-inline"> </form> --}}

      <div class="form-inline">
          {{-- Sends you back to the previous URL regardless of where you are --}}
          @if( Session::has('let_url') )
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ Session::get('let_url') }}"> Go Back </a>
          @else
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('posts.index') }}"> All Posts </a>
          @endif

        @auth
          @if( auth()->user()->id == $posts->user_id || auth()->user()->role == 'superadmin')
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('posts.edit', $posts->id) }}"> Edit Post </a>
            <a class="btn btn-danger" style="margin-right: 5px" href="{{ route('posts.delete', $posts->id ) }}"> Delete </a>
          @endif
          @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && $posts->flagged == '0')
            <a class="btn btn-warning" style="margin-right: 5px" href="{{ route('adminpanel.flagpost', $posts->id) }}"> Flag Post </a>
          @endif
          @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && $posts->flagged == '1')
            <a class="btn btn-success" style="margin-right: 5px" href="{{ route('adminpanel.unflagpost', $posts->id) }}"> Unflag Post </a>
          @endif
        @endauth
      </div>

      <br>
      @if($posts->cover_image != 'noimage.jpg')
      <div class="col-md-6">
        <img src="/storage/cover_images/{{$posts->cover_image}}" class="w-100">
      </div>
      @endif
  @else
      <h1> This Content Has Been Flagged as Inappropriate by an Admin </h1>
      <p> Please Contact Admins for More Detail </p>
  @endif

@if(auth()->user()->locked == 'unlocked')
  <form action="{{ route('comments.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="comment">Comment</label>
      <textarea class="form-control" rows="3" name="comment" id="comment" placeholder="Enter Comment">{{ old('body') }}</textarea>
    </div>
    <input type="hidden" name="post_id" id="post_id" value="{{ $posts->id }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="username" id="username" value="{{ auth()->user()->name }}">
    <input type="submit" class="btn btn-primary" value="Submit">
  </form>
  <br>
@elseif(auth()->user()->locked == 'locked')



  <h4 class="text-center"> Due to not following our community standards, you are currently locked from on any posts.</h4>
  <h4 class="text-center"> Contact Admins for furthur information. </h4>
@endif

  @foreach ($comments as $comment)
    @if($comment->flagged == '0')
      <div class="container py-3">
          <div class="card">
              <div class="row">
                  <div class="col-md-8 px-3 pt-3 my-auto">
                      <div class="card-block px-3 pb-2">
                          <h6>  Comment by <a href="{{ route('profile.display', $comment->user_id ) }}"> {{$comment->username}} : </a> </h6>

                          <h5 class="card-title"> {{$comment->comment}}</h5>
                          <p class="card-text"> Commented on: {{$comment->created_at}} </p>
                          @auth
                            @if( auth()->user()->id == $comment->user_id || auth()->user()->role == 'superadmin')
                              <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('comments.edit', $comment->id) }}"> Edit Comment </a>
                              <a class="btn btn-danger" style="margin-right: 5px" href="{{ route('comments.delete', $comment->id ) }}"> Delete Comment </a>
                            @endif
                            @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && (auth()->user()->id != $comment->user_id) && ($comment->flagged == '0') )
                              <a class="btn btn-warning" style="margin-right: 5px" href="{{ route('adminpanel.flagcomment', $comment->id) }}"> Flag Comment </a>
                            @endif
                            @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && (auth()->user()->id != $comment->user_id) && ($comment->flagged == '1') )
                              <a class="btn btn-success" style="margin-right: 5px" href="{{ route('adminpanel.unflagcomment', $comment->id) }}"> Unflag Comment </a>
                            @endif
                          @endauth
                      </div>
                  </div>
              </div>
          </div>
      </div>
    @else
      <div class="container py-3">
          <div class="card">
              <div class="row">
                  <div class="col-md-8 px-3 pt-3 my-auto">
                      <div class="card-block px-3 pb-2">
                          <h6>  Comment by <a href="{{ route('profile.display', $comment->user_id ) }}"> {{$comment->username}} : </a> </h6>
                          <h5 class="card-title"> This Comment Has Been Removed due to Violation of Rules <span class="badge badge-danger"> Flagged !! </span> </h5>
                          @if( auth()->user()->id == $comment->user_id )
                            <h5 class="card-title"> {{$comment->comment}}</h5>
                          @endif
                          <p class="card-text"> Commented on: {{$comment->created_at}} </p>
                          @auth
                            @if( auth()->user()->id == $comment->user_id || auth()->user()->role == 'superadmin')
                              <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('comments.edit', $comment->id) }}"> Edit Comment </a>
                              <a class="btn btn-danger" style="margin-right: 5px" href="{{ route('comments.delete', $comment->id ) }}"> Delete Comment </a>
                            @endif
                            @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && (auth()->user()->id != $comment->user_id) && ($comment->flagged == '0') )
                              <a class="btn btn-warning" style="margin-right: 5px" href="{{ route('adminpanel.flagcomment', $comment->id) }}"> Flag Comment </a>
                            @endif
                            @if( (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') && (auth()->user()->id != $comment->user_id) && ($comment->flagged == '1') )
                              <a class="btn btn-success" style="margin-right: 5px" href="{{ route('adminpanel.unflagcomment', $comment->id) }}"> Unflag Comment </a>
                            @endif
                          @endauth
                      </div>
                  </div>
              </div>
          </div>
      </div>
    @endif
  @endforeach

@endsection
