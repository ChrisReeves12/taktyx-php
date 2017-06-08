@extends('frontend.layout.default')
@section('content')

    <img height="65" src="{{$service->image->path}}" alt="">
    <h2>Edit Service</h2>
    <hr>

    <!-- Display errors -->
    @if(Session::has('geolocator_error'))
        <div style="margin-top: 10px;" class="alert alert-danger">{{session('geolocator_error')}}</div>
    @endif

    @if(count($errors) > 0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{route('update_service', ['id' => $service->id])}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
            <label for="name">@lang('Service Name')</label>
            <input type="text" name="name" class="form-control" value="{{$service->name}}">
        </div>
        <div class="form-group">
            <label for="email">@lang('Email')</label>
            <input type="email" name="email" class="form-control" value="{{$service->email}}">
        </div>
        <div class="form-group">
            <label for="name">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <label for="subcategory">@lang('Select Subcategory')</label>
        <div class="form-group">
            <select id="subcategory" class="form-control" name="subcategory">
                @foreach($subcategories as $subcategory)
                    <option
                            @if($subcategory->id == $service->subcategory->id)
                                    selected="selected"
                            @endif
                            value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="first_name">@lang('First Name')</label>
            <input type="text" name="first_name" class="form-control" value="{{$service->first_name}}">
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Last Name')</label>
            <input type="text" name="last_name" class="form-control" value="{{$service->last_name}}">
        </div>
        <div class="form-group">
            <label for="address_line_1">@lang('Address Line 1')</label>
            <input type="text" name="address_line_1" class="form-control" value="{{$service->address->line_1}}">
        </div>
        <div class="form-group">
            <label for="address_line_2">@lang('Address Line 2')</label>
            <input type="text" name="address_line_2" class="form-control" value="{{$service->address->line_2}}">
        </div>
        <div class="form-group">
            <label for="postal_code">@lang('City, State/Province/Region, Postal Code')</label>
            <input type="text" name="postal_code" class="form-control" value="{{$service->address->postal_code}}">
        </div>
        <div class="form-group">
            <label for="country_id">@lang('Country or Territory')</label>
            <select id="country" class="form-control" name="country_id">
                @foreach($countries as $country)
                    <option
                            @if($country->id == $service->address->country->id)
                            selected="selected"
                            @endif
                            value="{{$country->id}}">{{$country->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="takt_enabled">Enable Takts</label><br>
            <input type="checkbox"
                   @if($service->takt_enabled)
                       checked="checked"
                   @endif
                   name="takt_enabled">
        </div>
        <div class="form-group">
            <label for="summary">@lang('About This Service')</label>
            <textarea name="summary" id="summary" class="form-control" cols="20" rows="5">{{$service->summary}}</textarea>
        </div>
        <div class="form-group">
            <label for="keywords">@lang('Keywords (separate with a comma)')</label>
            <textarea name="keywords" id="keywords" class="form-control" cols="20" rows="5">{{$service->keywords}}</textarea>
        </div>
        <input type="submit" class="btn btn-primary" name="submit">
    </form>

@endsection