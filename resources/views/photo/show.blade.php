@extends('layouts.app')

@section('content')

  <a class="btn btn-primary" href="{{ route('profile.display', $photodata->userid ) }}"> Go to {{ $user->name }}'s profile </a>
  <br>
  <br>
  <div class="form-inline">
      @auth
          <a class="btn btn-primary" href="{{ route('photo.display', $photodata->userid) }}"> Go Back </a>
          @if(Auth::user()->id == $photodata->userid)
            <a class="btn btn-danger" style="margin-left: 5px" href="{{ route('photo.delete', $photodata->id) }}"> Delete </a>
          @endif

          @if(Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
              @if($photodata->flagged == '0')
                  <a class="btn btn-warning" style="margin-left: 5px" href="{{ route('adminpanel.flagphoto', $photodata->id) }}"> Flag Photo </a>
              @endif
              @if($photodata->flagged == '1')
                  <a class="btn btn-success" style="margin-left: 5px" href="{{ route('adminpanel.unflagphoto', $photodata->id) }}"> Unflag Photo <span style="margin-left: 5px" class="badge badge-danger"> Flagged !! </span> </a>
              @endif
          @endif

        @endauth
  </div>

  <br>
  <h3> {{ $photodata->description }} </h3>
  <img src="/storage/upload_photo/{{ $photodata->filename }}" alt="{{ $photodata->filename }}">
  <p class="card-text"> Posted on: {{ $photodata->created_at }}</p>

@endsection
