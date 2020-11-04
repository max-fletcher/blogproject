@extends('layouts.app')

@section('content')

    <br>
    <h3> Friends List: </h3>
    <br>

    @if( count($friends) > 0 )
      <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
              <th style="width: 15%"> ID </th>
              <th style="width: 50%"> Friend Name </th>
              <th style="width: 15%"> Send Message</th>
              <th style="width: 15%"> Unfriend</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($friends as $friend)
                <tr>
                  <td> {{ $friend->id }} </td>
                  <td> <a href="{{ route('profile.display', $friend->id) }}"> {{ $friend->name }} </a> </td>
                  <td> <a href="{{ route('messages.seeMessage', [auth()->user()->id, $friend->id]) }}" class="btn btn-success pt-2 pb-2"> See Messages <span class="badge badge-light"> {{ $friend->msg_number }} </span> </a></td>
                  <td> <a href="{{ route('messages.unfriend', [ auth()->user()->id, $friend->id] ) }}" class="btn btn-warning pt-2 pb-2">  Unfriend  </a> </td>
                </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <h5 class="text-center"> Your Friend List is Currently Empty !! </h5>
      <br>
    @endif

    <h3> Sent Requests: </h3>
    <br>
    @if(count($sent_to_user) > 0)
      <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
              <th style="width: 15%"> ID </th>
              <th style="width: 50%"> Friend Name </th>
              <th style="width: 30%"> Status </th>
            </tr>
        </thead>
        <tbody>
              @foreach ($sent_to_user as $sent)
                <tr>
                  <td> {{ $sent->id }} </td>
                  <td> <a href="{{ route('profile.display', $sent->id) }}"> {{ $sent->name }} </a> </td>
                  <td> <span class="alert alert-primary pt-2 pb-2">  Pending Acceptance  </span> </td>
                </tr>
              @endforeach
        </tbody>
      </table>
    @else
      <h5 class="text-center"> You Currently Don't Have Any Active Friend Requests That You Sent !! </h5>
    @endif

    <h3>Received Requests:</h3>
    <br>
    @if(count($received_from_user) > 0)
      <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
              <th style="width: 15%"> ID </th>
              <th style="width: 50%"> Friend Name </th>
              <th style="width: 30%"> Pending Acceptance</th>
            </tr>
        </thead>
        <tbody>
              @foreach ($received_from_user as $rec)
                <tr>
                  <td> {{ $rec->id }} </td>
                  <td> <a href="{{ route('profile.display', $rec->id ) }}"> {{ $rec->name }} </a> </td>
                  <td>
                    <a href="{{ route('messages.acceptRequest', [ $rec->id, auth()->user()->id ] ) }}" class="btn btn-primary pt-2 pb-2">  Accept  </a>
                    <a href="{{ route('messages.rejectRequest', [ $rec->id, auth()->user()->id ] ) }}" class="btn btn-danger pt-2 pb-2">  Reject  </a>
                  </td>
                </tr>
              @endforeach
        </tbody>
      </table>
    @else
      <h5 class="text-center">You Currently Don't Have Any Active Friend Requests Sent To You !!</h5>
      <br>
    @endif
@endsection
