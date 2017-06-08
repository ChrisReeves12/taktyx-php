@extends('frontend.layout.default')
@section('content')

    <h1>Select Service</h1>
    <h4>{{$employee->first_name}} {{$employee->last_name}}</h4>
    <hr>
    <form method="post" action="{{route('employee_select_service_do')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="select_service">Select Service</label>
            <select name="select_service" class="form-control">
                @foreach($services as $service)
                    <option value="{{$service->id}}">{{$service->name}}</option>
                @endforeach
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="submit">
    </form>

@endsection