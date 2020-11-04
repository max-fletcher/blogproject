@extends('layouts.app')

@section('content')
<div class="">
    <h1> Posts Index Page </h1>

    @if ( auth()->user()->locked == 'unlocked' )
      <a class="btn btn-primary" href="{{ route('posts.create') }}"> Create Post </a>
    @elseif ( auth()->user()->locked == 'locked' )
      <a class="btn btn-primary disabled" href="{{ route('posts.create') }}"> Create Post <span class="badge badge-danger">Locked</span> </a>
    @endif
</div>


@if( count($posts) > 0 )
    @foreach ($posts as $post)
      @if($post->flagged == '0')
        <div class="container py-3">
            <div class="card">
                <div class="row">
                    @if($post->cover_image != 'noimage.jpg')
                      <div class="col-md-3">
                          <img src="/storage/cover_images/{{$post->cover_image}}" class="w-100">
                      </div>
                    @endif
                      <div class="col-md-8 px-3 pt-3 my-auto">
                          <div class="card-block px-3">
                              <h4 class="card-title"><a href="{{ route('posts.show', $post->id) }}"> {{$post->title}} </a></h4>
                              <p class="card-text"> Written on: {{$post->created_at}} by <a href="{{ route('profile.display',$post->user_id ) }}"> {{$post->user->name}} </a></p>
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
                        <div class="card-block px-3">
                          <img class="card-img-top" src="/storage/warning_images/photo_removed.png" style="width: 300px" alt="Card image cap">
                          @if(auth()->user()->id == $post->user_id || auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
                            <h4 class="card-title"><a href="{{ route('posts.show', $post->id) }}"> {{$post->title}} </a></h4>
                          @endif
                            <h4 class="card-title"> This Post Has Been Removed due to Violation of Rules </a></h4>
                            <p class="card-text"> Written on: {{$post->created_at}} by <a href="{{ route('profile.display',$post->user_id ) }}"> {{$post->user->name}} </a></p>
                    </div>
                </div>
            </div>
        </div>
      @endif
    @endforeach
    @else
      <br>
      <br>
      <h3 class="text-center">There are no Posts Available !!</h3>
    @endif
@endsection
