@extends('frontend.layout.default')

@section('content')

    <h1>Address</h1>
    <hr>
    <p>
        {{$line_1}}<br>
        {{$line_2}}<br>
        {{$postal_code}}
    </p>

    <b>Latitude: </b>{{$latitude}}<br>
    <b>Longitude: </b>{{$longitude}}

@endsection