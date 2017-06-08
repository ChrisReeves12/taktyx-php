@extends('frontend.layout.default')



@section('content')

    <h1>{{$service->name}}</h1>
    <p>{{$service->subcategory->category->name}} > {{$service->subcategory->name}} </p>
    <hr>
    <img class="img-rounded" height="128" src="{{$service->image->path}}" alt="{{$service->name}}">
    <p>

    <!-- display status -->
	<?php switch($service->status){
	case "open":
	?>
    <b style="color: #3c763d">OPEN</b><br>
	<?php break; ?>
	<?php case "closed": ?>
    <b style="color: #c12e2a">CLOSED</b><br>
	<?php break; ?>
	<?php case "busy": ?>
    <b style="color: indigo">BUSY</b><br>
	<?php break; ?>
	<?php } ?>

    <p>
        @if($service->status == 'open')
            <a href="{{route('new_takt', ['service_id' => $service->id])}}" class="btn btn-primary">Send Takt</a>
        @endif($service->status == 'busy')
    </p>

    @if(!empty($service->phone_number))
        Phone: ({{$service->phone_number->area_code}}) {{$service->phone_number->number}}<br>
    @endif
        Referral: {{$service->first_name}} {{$service->last_name}}<br>
    </p>

    <!-- Display that a user isn't allowing Takts if they aren't -->
    @if(!$service->takt_enabled)
        <div class="alert alert-danger">
            This business doesn't allow Takts to be sent.
        </div>
    @endif

    <h4>Address</h4>
    <hr>
    <p>
        {{$service->address->line_1}}<br>
        {{$service->address->line_2}}<br>
        {{$service->address->postal_code}}<br>
    </p>
    <h4>About {{$service->name}}</h4>
    <hr>
    <p>{{$service->summary}}</p>



@endsection