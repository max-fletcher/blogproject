@extends('layouts.app')

@section('content')
  <div class="jumbotron text-center">
      <h1>Profiles Page</h1>
      @if( auth()::user()->profile()->profile_photo != 'null')
        <img src="{{}}" alt="">
      @endif    
  </div>

@endsection
