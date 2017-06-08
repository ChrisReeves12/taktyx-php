@extends('frontend.layout.default');

@section('content')

    <h1>Send Takt</h1>
    <h4>{{$service->name}}</h4>
    <hr>
    <form method="post" action="{{route('store_takt', ['service_id' => $service->id])}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="content">Message</label><br>
            <textarea name="content" class="form-control" cols="30" rows="10"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" value="Send Takt Message" name="submit">
    </form>
@endsection