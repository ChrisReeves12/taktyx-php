@extends('frontend.layout.default')
@section('content')

    <h1>Forgot Password</h1>
    <h3>Employee</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if(Session::has('error'))
                    <div class="alert alert-danger">{{session('error')}}</div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ route('employee_forgot_password_do') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">@lang('E-Mail Address')</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('Send Password Reset Link')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection