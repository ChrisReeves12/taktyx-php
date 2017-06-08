@extends('frontend.layout.default')
@section('content')

    <h1>Taktyx Business</h1>
    <hr>
    @if(Session::has('business_activation_sent'))
        <div class="alert alert-info">@lang(session('business_activation_sent'))</div>
    @endif
    @if(Session::has('business_activated'))
        <div class="alert alert-success">@lang(session('business_activated'))</div>
    @endif
    @if(Session::has('business_activation_fail'))
        <div class="alert alert-danger">@lang(session('business_activation_fail'))</div>
    @endif
    @if(Session::has('business_login_fail'))
        <div class="alert alert-success">@lang(session('business_login_fail'))</div>
    @endif
    @if(Session::has('reset_email_sent'))
        <div class="alert alert-success">@lang(session('reset_email_sent'))</div>
    @endif
    @if(Session::has('reset_email_fail'))
        <div class="alert alert-danger">@lang(session('reset_email_fail'))</div>
    @endif

    <ul>
        @if($business_auth_service->non_business())
            <li><a href="{{route('create_business')}}">@lang('Register a Business')</a></li>
            <li><a href="{{route('business_login')}}">@lang('Business Sign-In')</a></li>
            <li><a href="{{route('employee_login')}}">@lang('Employee Sign-In')</a></li>
        @else
            @if(!empty($business_auth_service->business()))
                @if($business_auth_service->business()->status == "active")
                    <li><a href="{{route('business_resend_activation')}}">Resend Activation Email</a></li>
                @endif

                <li><a href="{{route('business_dashboard')}}">@lang("Edit " . $business_auth_service->business()->name)</a></li>
                <li><a href="{{route('create_service')}}">@lang('Add Service')</a></li>
                <li><a href="{{route('business_logout')}}">@lang('Log Out')</a></li>
            @endif
        @endif
    </ul>
    @if(!$business_auth_service->non_business())
        <h1 class="text-center">My Services</h1>
        <hr>
        <ul>
            @foreach($services as $service)
            <li>
                <img class="img img-rounded" height="64" src="{{$service->image->path}}" alt="">
                <h4>{{$service->name}}</h4>
                {{$service->subcategory->name}}<br>

                <!-- display status -->
                <?php switch($service->status){
                    case "open":
                ?>
                    <b style="color: #3c763d">OPEN</b><br>
				    <?php break; ?>
                <?php case "closed": ?>
                    <b style="color: #c12e2a">CLOSED</b><br>
				    <?php break; ?>
				<?php case "busy": ?>
                <b style="color: indigo">BUSY</b><br>
				<?php break; ?>
                <?php } ?>

                <a href="{{route('edit_service', ['id' => $service->id])}}">Edit Service</a> |
                <a href="{{route('edit_service_phone', ['id' => $service->id])}}">Edit Phone Number</a> |
                @if($service->is_enterprise())
                    <a href="{{route('employees', ['service_id' => $service->id])}}">Manage Employees</a> |
                @endif
                <a href="{{route('show_service_profile', ['id' => $service->id])}}">See Profile</a> |
                <a href="{{route('service_takts', ['id' => $service->id])}}">View Takts</a>
                <form action="{{route('update_service_status', ['id' => $service->id])}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="status">Change Status</label>
                        <select name="status">
                            <option value="open">Open</option>
                            <option value="busy">Busy</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <input type="submit" value="Change Status" class="btn btn-primary" name="submit">
                </form>
                <div style="margin-top: 8px">
                    <form method="post" action="{{route('delete_service', ['id' => $service->id])}}">
                        {{csrf_field()}}
                        <input type="submit" value="Delete Service" class="btn btn-danger" name="submit">
                    </form>
                </div>
            </li><br>

            @endforeach
        </ul>
    @endif

@endsection