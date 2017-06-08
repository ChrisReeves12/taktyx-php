@extends('frontend.layout.default')
@section('content')

    <h1>Employees</h1>
    <h2>{{$service->name}}</h2>
    <hr>
    @if(Session::has('employee_permission_sent'))
        <div class="alert alert-info">@lang(session('employee_permission_sent'))</div>
    @endif
    @if(Session::has('employee_exists_already'))
        <div class="alert alert-info">@lang(session('employee_exists_already'))</div>
    @endif

    <div class="form-group">
        <a class="btn btn-secondary" href="{{route('employee_create', ['service_id' => $service->id])}}">Add New</a>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center">Profile</th>  
            <th class="text-center">Firstname</th>
            <th class="text-center">Lastname</th>
            <th class="text-center">Email</th>
            <th class="text-center">Phone Number</th>
            <th class="text-center">Address Line 1</th>
            <th class="text-center">Address Line 2</th>
            <th class="text-center">City/State/Postal</th>
            <th class="text-center">Admin</th>
          </tr>
        </thead>
        <tbody>
        @foreach($employees as $employee)
          <tr>
            <td><img src="{{$employee->image($service)->path}}" height="50" alt="{{$employee->first_name}} {{$employee->last_name}}"></td>
            <td>{{$employee->first_name}}</td>
            <td>{{$employee->last_name}}</td>
            <td>{{$employee->email}}</td>
            <td>
                @if(!empty($employee->phone_number($service)))
                    ({{$employee->phone_number($service)->area_code}}) {{$employee->phone_number($service)->number}}
                @endif
            </td>

            <td>
                @if(!empty($employee->address($service)))
                    {{$employee->address($service)->line_1}}
                @endif
            </td>

            <td>
                @if(!empty($employee->address($service)))
                    {{$employee->address($service)->line_2}}
                @endif
            </td>

            <td>
                @if(!empty($employee->address($service)))
                    {{$employee->address($service)->postal_code}}
                @endif
            </td>

            <td>
                @if($employee->is_admin($service))
                    <b style="color: red">ADMIN</b>
                @endif
            </td>
            <td>
                <a class="btn btn-info" href="{{route('employee_edit', ['service_id' => $service->id, 'id' => $employee->id ])}}">Edit</a>
            </td>
            @if($business_auth_service->business() == $service->business)
                <td>
                    <form action="{{route('employee_delete', ['service_id' => $service->id, 'id' => $employee->id])}}" method="post">
                        {{csrf_field()}} {{method_field('delete')}}
                        <input type="submit" class="btn btn-danger" value="Delete" name="delete">
                    </form>
                </td>
            @endif
          </tr>
        @endforeach
        </tbody>
      </table>

@endsection