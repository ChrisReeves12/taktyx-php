@extends('frontend.layout.default')
@section('content')
    <h1>Business Dashboard</h1>
    <h3>{{$business->name}}</h3>
    <hr>

    <!-- display error message -->
    @if(Session::has('must_activate'))
        <div class="alert alert-info">{{session('must_activate')}}</div>
    @endif

    <ul>
        <li><a href="{{route('edit_business')}}">@lang('Edit Account')</a></li>
        <li><a href="{{route('edit_business_address')}}">@lang('Edit Address')</a></li>
        <li><a href="{{route('edit_business_phone')}}">@lang('Edit Phone Number')</a></li>
    </ul>

@endsection