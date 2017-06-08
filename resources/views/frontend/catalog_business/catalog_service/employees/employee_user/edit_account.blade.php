@extends('frontend.layout.default')
@section('content')

    <h1>Edit Account</h1>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
                <form class="form-horizontal" role="form" method="POST"
                      action="{{ route('employee_update_account', ['service_id' => $service->id, 'id' => $employee->id]) }}">
                    {{ csrf_field() }}
                    {{method_field('PATCH')}}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">@lang('E-Mail Address')</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $employee->email }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>@lang($errors->first('email'))</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label for="first_name" class="col-md-4 control-label">@lang('First Name')</label>

                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $employee->first_name }}" required>

                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>@lang($errors->first('first_name'))</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label for="last_name" class="col-md-4 control-label">@lang('Last Name')</label>

                        <div class="col-md-6">
                            <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $employee->last_name }}" required>

                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>@lang($errors->first('last_name'))</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">@lang('Password')</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>@lang($errors->first('password'))</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-md-4 control-label">@lang('Confirm Password')</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                @lang('Update Employee')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection