@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h5>You Are Logged In.</h5>
                    <br>
                    <h5>Your Posts:</h5>

                  @if(count($posts) > 0)
                    <table class="table table-striped">
                        <tr>
                          <th> Post Title </th>
                          <th> Edit </th>
                          <th> Delete </th>
                        </tr>
                        @foreach ($posts as $post)
                          <tr>
                            <td><a href="{{ route('posts.show', $post->id) }}"> <strong> {{ $post->title }} </strong> </a></td>
                            <td> <a class="btn btn-primary" href="{{ route('posts.edit', $post->id) }}"> Edit </a> </td>
                            <td>
                              <form type="hidden" action="{{ route('posts.destroy', $post->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                    </table>
                  @else
                    <h3 class="text-center"> You Have No Posts !! </h3>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
