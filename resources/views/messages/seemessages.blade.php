@extends('layouts.app')

@section('content')
<h2> Messages Between You {{ $friend_data->name }} </h2>

<form action="{{ route('messages.sendMessage') }}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <label for="message">Enter Message Here</label>
    <textarea class="form-control" name="message" id="message" rows="3"></textarea>
  </div>
  <div class="form-group">
      <input type="hidden" name="user_id_from" id="user_id_from" value="{{ $user_data->id }}">
      <input type="hidden" name="user_id_to" id="user_id_to" value="{{ $friend_data->id }}">
      <input type="submit" class="btn btn-primary" value="Submit">
 </div>
</form>

<!-- Check if $all_messages is empty or not. If Empty, will go to at_else. -->
@if( count($all_messages) > 0 )
<div class="d-flex flex-row">
  <div class="p-8"> <strong> {{ $user_data->name }} </strong> Says: </div>
</div>

<div class="d-flex flex-row-reverse">
  <div class="p-8"> <strong> {{ $friend_data->name }} </strong> Says: </div>
</div>

@foreach ($all_messages as $message)
  @if($message->user_id_from == auth()->user()->id)
    <div class="d-flex flex-row">
      <div class="alert alert-primary p-6 font-weight-bold" role="alert"> {{$message->body}}
        <br>
        <small> Messaged at: {{ \Carbon\Carbon::parse($message->created_at)->format('H:i d/m/Y')}} </small>
      </div>
    </div>
  @else
    <div class="d-flex flex-row-reverse">
      <div class="alert alert-secondary p-6 font-weight-bold" role="alert"> {{$message->body}}
        <br>
        <small> Messaged at: {{ \Carbon\Carbon::parse($message->created_at)->format('H:i d/m/Y')}} </small>
      </div>
    </div>
  @endif
@endforeach
@else
  <h3> You currently have not exchanged messages between you and {{ $friend_data->name }} </h3>
@endif

@endsection
