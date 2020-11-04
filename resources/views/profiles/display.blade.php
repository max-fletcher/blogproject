@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profiles Page</h1>

    @auth
      @if($profile_data->id == $profile_id)
        <a class="btn btn-primary" href="{{ route('profile.edit', $profile_data->id) }}">Edit Profile</a>
      @endif
    @endauth
    <a class="btn btn-primary" href="{{ route('photo.display', $profile_data->id) }}">See Photo Gallery</a>
    <a class="btn btn-primary" href="{{ route('posts.myposts', $profile_data->id) }}">{{ $profile_data->name }}'s Posts</a>

    @if(auth()->user()->id != $profile_data->id)
      @if( $friend_request_flag == 0 )
        <a class="btn btn-primary" href="{{ route('messages.sendRequest', [ auth()->user()->id, $profile_data->id ]) }}">Send Friend Request</a>
      @endif
    @endif

    <br>
    <br>
    @if($profile_data->profile_image != '')
    <img class="rounded mx-auto d-block" style="width: 300px; height: 300px" src="/storage/profile_images/{{$profile_data->profile_image}}" alt="">
    <br>
    @else
      <img class="rounded mx-auto d-block" style="width: 300px; height: 300px" src="/storage/profile_images/no_image.png" alt="">
      <br>
    @endif

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bolder lead">Username </h5>
            <p class="card-text font-italic lead">{{$profile_data->name}}</p>
        </div>
    </div>

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bold lead">Interests</h5>
            @if( $profile_data->favourite_shows == '' )
            <p>Not edited Yet !!</p>
            @else
            <p class="card-text font-italic lead">
                {{$profile_data->interests}}
            </p>
            @endif
        </div>
    </div>

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bold lead">About Me</h5>
            @if( $profile_data->about_me == '' )
            <p>Not edited Yet !!</p>
            @else
            <p class="card-text font-italic lead">
                {{$profile_data->about_me}}
            </p>
            @endif
        </div>
    </div>

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bold lead">Favourite Shows</h5>
            @if( $profile_data->favourite_shows == '' )
            <p>Not edited Yet !!</p>
            @else
            <p class="card-text font-italic lead">
                {{$profile_data->favourite_shows}}
            </p>
            @endif
        </div>
    </div>

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bold lead">Favourite Movies</h5>
            @if($profile_data->favourite_movies == '')
                <p> Not edited Yet !!</p>
                @else
                <p class="card-text font-italic lead">
                    {{$profile_data->favourite_movies}}
                </p>
                @endif
        </div>
    </div>

    <div class="card text-center mb-3 ">
        <div class="card-body">
            <h5 class="card-title font-weight-bold lead">Favourite Books</h5>
            @if($profile_data->favourite_books == '')
                <p> Not edited Yet !!</p>
                @else
                <p class="card-text font-italic lead">
                    {{$profile_data->favourite_books}}
                </p>
                @endif
        </div>
    </div>
</div>

@endsection
