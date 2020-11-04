@extends('layouts.app')

@section('content')

<h1>{{ $user->name }}'s Photo Gallery</h1>
<a class="btn btn-primary" href="{{ route('profile.display', $user->id ) }}"> Go to {{ $user->name }}'s profile </a>
<br><br><br>

@if( auth()->user()->locked == 'unlocked' )

    @if($profile_id == $user->id)
    <h3> Create Gallery </h3>
      <form action="{{ route('photo.store') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
              <div class="form-group w-25">
                <label for="title">Photo Description</label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description">
              </div>
              <div class="input-group">
                  <div class="custom-file col-sm-3">
                      <input type="file" class="custom-file-input" name="upload_photo" id="upload_photo">
                      <label class="custom-file-label" for="upload_photo">Choose file</label>
                  </div>
              </div>
          </div>
          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Upload Photo">
          </div>
      </form>
    @endif


    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                @foreach($photodata as $photo)
                    @if( $photo->flagged == '0' )
                        <div class="col-md-4">
                            <div class="card mb-4 box-shadow">
                                <a href="{{ route('photo.showphoto', [ 'photoid' => $photo->id, 'userid' => $user->id ] ) }}">
                                    <img class="card-img-top" src="/storage/upload_photo/{{ $photo->filename }}" alt="{{ $photo->filename }}">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"> {{ $photo->description }} </h5>
                                    <p class="card-text"> Posted on: {{ $photo->created_at }}</p>
                                    <div class="form-inline">
                                        <a href="{{ route('photo.showphoto', [ 'photoid' => $photo->id, 'userid' => $user->id ] ) }}" class="btn btn-primary"> View </a>
                                        @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
                                            @if($photo->flagged == '0')
                                              <a style="margin-left: 5px" href="{{ route('adminpanel.flagphoto', $photo->id) }}" class="btn btn-warning"> Flag Photo </a>
                                            @endif
                                            @if($photo->flagged == '1')
                                              <a style="margin-left: 5px" href="{{ route('adminpanel.unflagphoto', $photo->id) }}" class="btn btn-success"> Unflag Photo </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @if( ( auth()->user()->id == $photo->userid ) || (auth()->user()->role == 'admin') || (auth()->user()->role == 'superadmin') )
                            <div class="col-md-4">
                                <div class="card mb-4 box-shadow">
                                    <a href="{{ route('photo.showphoto', [ 'photoid' => $photo->id, 'userid' => $user->id ] ) }}">
                                        <img class="card-img-top" src="/storage/upload_photo/{{ $photo->filename }}" alt="{{ $photo->filename }}">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title"> {{ $photo->description }} @if($photo->flagged == '1') <span class="badge badge-danger"> This Post is Flagged !! </span> @endif </h5>
                                        <p class="card-text"> Posted on: {{ $photo->created_at }}</p>
                                        <div class="form-inline">
                                            <a href="{{ route('photo.showphoto', [ 'photoid' => $photo->id, 'userid' => $user->id ] ) }}" class="btn btn-primary"> View </a>
                                            @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
                                                @if($photo->flagged == '0')
                                                    <a style="margin-left: 5px" href="{{ route('adminpanel.flagphoto', $photo->id) }}" class="btn btn-warning"> Flag Photo </a>
                                                @endif
                                                @if($photo->flagged == '1')
                                                    <a style="margin-left: 5px" href="{{ route('adminpanel.unflagphoto', $photo->id) }}" class="btn btn-success"> Unflag Photo </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                          <div class="card" style="width: 300px;">
                              <img class="card-img-top" src="/storage/warning_images/photo_removed.png" alt="Card image cap">
                              <div class="card-body">
                                  <h5 class="card-title"> Flagged !! </h5>
                                  <p class="card-text">This Photo has been Removed due to Violation of Rules !! </p>
                              </div>
                          </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@elseif(auth()->user()->locked == 'locked')
  <div style="background:transparent" class="jumbotron text-center">
    <h2> Due to not following our community standards, you are currently locked from uploading any new photos in your gallery. </h2>
    <h2> Contact Admins for furthur information. </h2>
  </div>
@endif

@endsection
