@extends('layouts.app')

@section('content')
  @if(count($all_friend_list) > 0)
    <table class="table table-striped">
      <thead class="thead-dark">
        <tr>
          <th style="width: 20%"> Friend ID </th>
          <th style="width: 50%"> Friend Name </th>
          <th style="width: 30%"> Messages </th>
        </tr>
      </thead>
      <tbody>
          @foreach ($all_friend_list as $friend)
            <tr>
              <td> {{ $friend->id }} </td>
              <td> {{ $friend->name }} </td>
              <td> <a href="{{ route('messages.seeMessage', [auth()->user()->id, $friend->id]) }}" class="btn btn-success pt-2 pb-2"> See Messages <span class="badge badge-light"> {{ $friend->msg_number }} </span> </a></td>
            </tr>
          @endforeach
      </tbody>
    </table>
  @else
    <br><br><br>
    <h3 class="text-center"> Your Friend List is Currently Empty !! </h3>
  @endif
@endsection
