@extends('frontend.layout.default')
@section('content')

    <h1 class="text-center">Edit Account</h1>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('update_customer') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label for="username" class="col-md-4 control-label">@lang('Username')</label>

                        <div class="col-md-6">
                            <input id="name" type="text" value="{{$customer->username}}" class="form-control" name="username" required autofocus>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">@lang('E-Mail Address')</label>

                        <div class="col-md-6">
                            <input id="email" value="{{$customer->email}}" type="email" class="form-control" name="email" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">@lang('Password')</label>

                        <div class="col-md-6">
                            <input id="password" placeholder="Leave blank if you don't want changed." type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm"  class="col-md-4 control-label">@lang('Confirm Password')</label>

                        <div class="col-md-6">
                            <input id="password-confirm" placeholder="Leave blank if you don't want changed." type="password"
                                   class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('Update')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection