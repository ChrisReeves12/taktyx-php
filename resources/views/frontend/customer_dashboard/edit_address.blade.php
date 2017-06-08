@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Update Address</h1>
    <hr>
    <form action="{{route('update_cust_address')}}" method="post">
        {{csrf_field()}}

        <div class="form-group">
            <label for="address_line_1" class="col-md-4 control-label">@lang('Address Line 1')</label>

            <div class="col-md-6">
                <input id="address_line_1" type="text"
                       value="{{empty($address->line_1) ? old('address_line_1') : $address->line_1}}"
                       class="form-control" name="address_line_1">
            </div>
        </div>

        <div class="form-group">
            <label for="address_line_2" class="col-md-4 control-label">@lang('Address Line 2')</label>

            <div class="col-md-6">
                <input id="address_line_2" value="{{empty($address->line_2) ? old('address_line_2') : $address->line_2}}"
                       type="text" class="form-control" name="address_line_2">
            </div>
        </div>

        <div class="form-group">
            <label for="postal_code" class="col-md-4 control-label">@lang('City, State/Province/Region, Postal Code')</label>

            <div class="col-md-6">
                <input id="postal_code" value="{{empty($address->postal_code) ? old('postal_code') : $address->postal_code}}"
                       type="text" class="form-control" name="postal_code">
            </div>
        </div>

        <div class="form-group">
            <label for="country" class="col-md-4 control-label">@lang('Country or Territory')</label>

            <div class="col-md-6">
                <select id="country" class="form-control" name="country_id">
                    @foreach($countries as $country)
                        <option
                                @if($country->id == $address->country_id)
                                selected="selected"
                                @endif
                                value="{{$country->id}}">{{$country->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <input type="submit" value="Update Address" class="btn btn-primary" name="submit">
        </div>
    </form>
    <!-- Display errors -->
    @if(Session::has('geolocator_error'))
        <div style="margin-top: 10px;" class="alert alert-danger">{{session('geolocator_error')}}</div>
    @endif
    @if(count($errors) > 0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>@lang($error)</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection