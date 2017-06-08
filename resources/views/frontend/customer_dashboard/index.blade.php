@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Customer Dashboard</h1>
    <h3 class="text-center">{{$customer->username}}</h3>
    <img src="{{$image->path}}" class="img-rounded" height="100">
    <hr>

    <a href="{{route('edit_customer')}}">Edit Account</a><br>
    <a href="{{route('edit_cust_address')}}">Edit Address</a><br>
    <a href="{{route('edit_customer_image')}}">Edit Profile Picture</a><br>
    <a href="{{route('edit_customer_phone')}}">Edit Phone Number</a><br>
    <a href="{{route('customer_takts')}}">My Takts</a>

@endsection