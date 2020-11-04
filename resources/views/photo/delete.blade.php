@extends('layouts.app')

@section('content')

      <h4> Are you sure you want to delete this post ?? </h4>
      <br>

      <img src="/storage/upload_photo/{{ $photodata->filename }}" alt="{{ $photodata->filename }}">

      <br>
      <br>

      <div class="form-inline">
        @auth
          @if(Auth::user()->id == $photodata->userid)
            <form type="hidden" style="margin-right: 5px" action="{{ route('photo.destroy', $photodata->id) }}" method="post">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger"> Yes </button>
            </form>
            <a class="btn btn-primary" style="margin-right: 5px" href="{{ route('photo.show', $photodata->id) }}"> No </a>
          @endif
        @endauth
      </div>

@endsection
