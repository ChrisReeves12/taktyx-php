@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Edit Employee Profile Picture</h1>
    <hr>
    <img src="{{$image->path}}" class="img-rounded" height="100">
    <hr>
    <form action="{{route('employee_update_image', ['service_id' => $service->id, 'id' => $employee->id])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
            <label for="name">Image</label>
            <input type="file" class="form-control" name="image">
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