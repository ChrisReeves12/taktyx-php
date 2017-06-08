@extends('frontend.layout.default')
@section('content')

    <link href="{{ static_assets('public/css/frontend/chat.css') }}" rel="stylesheet" type="text/css"/>

    <h1>See Takt</h1>
    <hr>

    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <ul class="chat">

                            <!-- Message -->
                            <li class="left clearfix"><span class="chat-img pull-left">
                            <img height="32" src="{{$author->image->path}}" alt="User Avatar" class="img-circle" />
                            </span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <strong class="primary-font">{{$author->username}}</strong> <small class="pull-right text-muted">
                                            <span class="glyphicon glyphicon-time"></span>{{$takt->created_at->diffForHumans()}}</small>
                                    </div>
                                    <p>
                                        {{$takt->content}}
                                    </p>
                                </div>
                            </li>

                            @foreach($takt->replies as $reply)
                                <li class="right clearfix"><span class="chat-img pull-right">
                            <img height="32" src="{{$reply->author->image->path}}" alt="User Avatar" class="img-circle" />
                            </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>{{$reply->created_at->diffForHumans()}}</small>
                                            @if($reply->replyable_type == 'customer')
                                                <strong class="pull-right primary-font">{{$reply->author->username}}</strong>
                                            @elseif($reply->replyable_type == 'catalog_service')
                                                <strong class="pull-right primary-font">{{$reply->author->name}}</strong>
                                            @endif
                                        </div>
                                        <br>
                                        <p>
                                            {{$reply->content}}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                            <form action="{{route('store_reply', ['replyable_type' => $replyable_type ,'replyable_id' => $replyable_id, 'takt_id' => $takt->id])}}" method="post">
                                {{csrf_field()}}
                                <div class="panel-footer">
                                    <div class="input-group">
                                        <input id="btn-input" name="reply_content" type="text" class="form-control input-sm" placeholder="Type your reply here..." />
                                        <span class="input-group-btn">
                                            <button class="btn btn-warning btn-sm" id="btn-chat">Send</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Session::has('error'))
        <div class="alert alert-danger" style="margin-top: 10px;">{{session('error')}}</div>
    @endif

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