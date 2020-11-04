@extends('layouts.app')

@section('content')

<h1> Admin Panel </h1>
<br>

<div class="row">
    <h2 class=""> Users </h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th> ID </th>
                <th> Username </th>
                <th> E-mail Address </th>
                <th> Number of Warnings </th>
                <th> Send Warning Mail </th>
                <th> Lock/Unlock </th>
                <th> Role </th>
                @if(auth()->user()->role == 'superadmin')
                  <th> Change Role </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach( $profiles as $profile )
              <tr class="{{ $profile->id }}" id="{{ $profile->id }}">
                  <th> {{ $profile->id }} </th>

                  <td> <a href="{{ route('profile.display' , $profile->id) }}"> {{ $profile->name }} </a> </td>
                  <td> {{ $profile->email }} </td>
                  <td> {{ $profile->warnings }} </td>

                  @if ($profile->role == 'superadmin')
                    <td> <div class="btn btn-dark"> Cannot Warn Superadmin </div> </td>
                  @elseif ($profile->id == auth()->user()->id && $profile->role == 'admin')
                    <td> <div class="btn btn-dark"> Cannot Warn Self </div> </td>
                  @elseif ($profile->role == 'user' || ($profile->role == 'admin' && $profile->id != auth()->user()->id ) )
                    <td> <a class="btn btn-warning" href="{{ route('adminpanel.sendwarningemail', $profile->id) }}"> Send Warning Mail</a> </td>
                  @endif

                  @if ($profile->role == 'superadmin')
                    <td> <div class="btn btn-dark"> Cannot Lock Superadmin </div> </td>
                  @elseif ($profile->id == auth()->user()->id && $profile->role == 'admin')
                    <td> <div class="btn btn-dark"> Cannot Lock/Unlock Self </div> </td>
                  @elseif ($profile->role == 'user' || ($profile->role == 'admin' && $profile->id != auth()->user()->id ) )
                    @if($profile->locked == 'unlocked')
                      <td> <a class="btn btn-danger" href="{{ route('adminpanel.lockuser', $profile->id) }}"> Lock User </a> </td>
                    @elseif($profile->locked == 'locked')
                      <td> <a class="btn btn-danger" href="{{ route('adminpanel.unlockuser', $profile->id) }}"> Unlock User </a> </td>
                    @endif
                  @endif

                  <td> {{ $profile->role }} </td>

                  @if(auth()->user()->role == 'superadmin')
                    @if($profile->role == 'user')
                      <td> <a class="btn btn-info" href="{{ route('adminpanel.assignadmin', $profile->id) }}"> Assign admin role </a> </td>
                    @elseif($profile->role == 'admin')
                      <td> <a class="btn btn-info" href="{{ route('adminpanel.assignuser', $profile->id) }}"> Assign user role </a> </td>
                    @elseif($profile->role == 'superadmin')
                       <td> <div class="btn btn-dark"> Cannot Be Changed </div> </td>
                    @endif
                  @endif
              </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
