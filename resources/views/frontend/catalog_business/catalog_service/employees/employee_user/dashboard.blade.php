@extends('frontend.layout.default')
@section('content')

    <h1>Employee Dashboard</h1>
    <h2>{{$employee->first_name}} {{$employee->last_name}}</h2>
    <h4>{{$current_service->name}}</h4>
    <hr>
    <img src="{{$employee->image($current_service)->path}}" height="64"><br><br>
    <ul>
        <li><a href="{{route('employee_edit_account', ['service_id' => $current_service->id, 'id' => $employee->id])}}">Edit Account</a></li>
        <li><a href="{{route('employee_edit_image', ['service_id' => $current_service->id, 'id' => $employee->id])}}">Edit Profile Photo</a></li>
        <li><a href="{{route('employee_logout')}}">Log Out</a></li>
    </ul>
    <hr>

    <form method="post" action="{{route('employee_select_service_do')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="select_service">Select Service</label>
            <select name="select_service" class="form-control">
                @foreach($services as $service)
                    <option
                            @if($service->id == $current_service->id)
                                selected="selected"
                            @endif
                            value="{{$service->id}}">{{$service->name}}</option>
                @endforeach
            </select>
        </div>
        <input type="submit" class="btn btn-primary" value="Select Service" name="submit">
    </form>

    @if($employee->is_admin($current_service))
        <hr>
        <h4>Admin Tools</h4>
        <hr>
        <form method="post" action="{{route('employee_manage_employees', ['service_id' => $current_service->id])}}">
            {{csrf_field()}}
            <input type="submit" class="btn btn-success" value="Manage Employees" name="submit">
        </form>
    @endif


@endsection