@extends('frontend.layout.default')
@section('content')

    <h1>My Takts</h1>
    <h4>{{$name}}</h4>
    <hr>
    <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th class="text-center">From</th>
            <th class="text-center">To</th>
            <th class="text-center">Message</th>
          </tr>
        </thead>
        <tbody>

        @foreach($takts as $takt)
          <tr>

            <td><a href="{{route('view_takt',
            ['taktable_type' => $taktable_type, 'taktable_id' => $taktable_id, 'takt_id' => $takt->id])}}"
                   class="btn btn-info">View</a></td>

            <td><a href="#" class="btn btn-danger">Delete</a></td>
            <td>{{$takt->author->username}}</td>
            <td>{{$takt->recipient->name}}</td>
            <td>{{str_limit($takt->content, 140)}}</td>
          </tr>
        @endforeach

        {{$takts->links()}}

        </tbody>
      </table>
@endsection