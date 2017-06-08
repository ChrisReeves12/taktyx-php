@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Edit Employee Phone Number</h1>
    <hr>
    <form action="{{route('employee_update_phone', ['service_id' => $service->id, 'id' => $employee->id])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
            <label for="area_code">Area Code</label>
            <input type="text" value="{{empty($phone_number->area_code) ? old('area_code') : $phone_number->area_code}}"
                   class="form-control" name="area_code">
        </div>
        <div class="form-group">
            <label for="number">Phone Number (do not use dashes or spaces)</label>
            <input type="text" value="{{empty($phone_number->number) ? old('number') : $phone_number->number}}"
                   class="form-control" name="number">
        </div>
        <div class="form-group">
            <label for="country" class="col-md-4 control-label">@lang('Country')</label>
            <select id="country" class="form-control" name="country_id">
                @foreach($countries as $country)
                    <option
                            @if($country->id == $phone_number->country_id)
                            selected="selected"
                            @endif
                            value="{{$country->id}}">{{$country->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="submit">
    </form>

    <!-- Display errors -->
    @if(count($errors) > 0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection