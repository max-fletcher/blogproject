@extends('layouts.app')

@section('content')
  <h1> Edit Profile </h1>
    <form action="{{ route('profile.update', $profile_data->id) }}" method="post" enctype="multipart/form-data">
      @method('PUT')
      @csrf

      <label for="interests">Interests:</label>
      <textarea class="form-control" rows="4" name="interests" id="interests" placeholder="{{$profile_data->interests}}">{{ $profile_data->interests }}</textarea>

      <label for="about_me">About Me:</label>
      <textarea class="form-control" rows="4" name="about_me" id="about_me" placeholder="{{$profile_data->about_me}}">{{ $profile_data->about_me }}</textarea>

      <label for="favourite_shows">Favourite Shows:</label>
      <textarea class="form-control" rows="4" name="favourite_shows" id="favourite_shows" placeholder="{{$profile_data->favourite_shows}}">{{ $profile_data->favourite_shows }}</textarea>

      <label for="favourite_movies">Favourite Movies:</label>
      <textarea class="form-control" rows="4" name="favourite_movies" id="favourite_movies" placeholder="{{$profile_data->favourite_movies}}">{{ $profile_data->favourite_movies }}</textarea>

      <label for="favourite_books">Favourite Books:</label>
      <textarea class="form-control" rows="4" name="favourite_books" id="favourite_books" placeholder="{{$profile_data->favourite_books}}">{{ $profile_data->favourite_books }}</textarea>

      <br>
      <div class="form-group">
      <p> Update Profile Photo: </p>
      <input type="file" name="profile_image" id="profile_image">
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
