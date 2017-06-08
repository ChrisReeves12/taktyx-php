@extends('frontend.layout.default')
@section('content')

    <h1>Edit Employee</h1>
    <h2>{{$employee->first_name}} {{$employee->last_name}}</h2>
    <h4>{{$service->name}}</h4>
    @if($employee->is_admin($service))
        <h6 style="color: red"><b>ADMIN</b></h6>
    @endif
    <hr>
    <img class="img-rounded" height="128" src="{{$employee->image($service)->path}}" alt=""><br><br>
    <a href="{{route('employee_edit_address', ['service_id' => $service->id, 'id' => $employee->id])}}">Edit Address</a><br>
    <a href="{{route('employee_edit_phone', ['service_id' => $service->id, 'id' => $employee->id])}}">Edit Phone Number</a><br>
    <a href="{{route('employee_edit_image', ['service_id' => $service->id, 'id' => $employee->id])}}">Edit Profile Image</a><br>

    <!-- Make sure that only a business logged in with the right credentials can change this -->
    @if($business_auth_service->business() == $service->business)
        <hr>
        <form method="post" action="{{route('change_admin', ['service_id' => $service->id, 'id' => $employee->id])}}">
            {{csrf_field()}}
            @if(!$employee->is_admin($service))
                <input type="submit" class="btn btn-success" value="Make Admin" name="change_admin">
            @else
                <input type="submit" class="btn btn-danger" value="Revoke Admin" name="change_admin">
            @endif
        </form>
    @endif

@endsection