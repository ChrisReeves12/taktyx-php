@extends('frontend.layout.default')

@section('content')

    <h1>@lang('Forgot Password')</h1>
    <hr>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <form class="form-horizontal" role="form" method="POST" action="{{ route('frontend_auth_forgot_password_email') }}">
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