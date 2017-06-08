@extends('frontend.layout.default')

@section('content')

    <h1 class="text-center">@lang('Employee Sign-In')</h1>
    <hr>

    @if(Session::has('login_fail'))
        <div class="alert alert-danger">{{session('login_fail')}}</div>
    @endif
    @if(Session::has('info'))
        <div class="alert alert-info">{{session('info')}}</div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
    @endif

    <form class="form-horizontal" method="POST" action="{{route('employee_login')}}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">@lang('E-Mail Address')</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

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
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    @lang('Login')
                </button>

                <a class="btn btn-link" href="{{route('employee_forgot_password')}}">
                    @lang('Forgot Your Password?')
                </a>
            </div>
        </div>

    </form>

@endsection