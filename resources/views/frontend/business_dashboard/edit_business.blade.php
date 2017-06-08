@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Edit Business</h1>
    <hr>
    <form action="{{route('update_business')}}" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="name">Name</label>
            <input name="name" type="text" value="{{empty($business->name) ? old('name') : $business->name}}"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" value="{{empty($business->email) ? old('email') : $business->email}}"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Password Confirmation</label>
            <input name="password_confirmation" type="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input name="first_name" value="{{empty($business->first_name) ? old('first_name') : $business->first_name}}"
                   type="text" class="form-control">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input name="last_name" value="{{empty($business->last_name) ? old('last_name') : $business->last_name}}"
                   type="text" class="form-control">
        </div>

        <div class="form-group">
            <label for="summary">About Your Business</label>
            <textarea name="summary" class="form-control" cols="20" rows="5">{{$business->summary}}</textarea>

        </div>

        <input type="submit" class="btn btn-primary" name="submit">
    </form>

    <!-- Display errors -->
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