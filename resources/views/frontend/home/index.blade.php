@extends('frontend.layout.default')

@section('content')

    <!-- Flash messages -->
    @if(Session::has('info'))
        <div class="alert alert-info">{{session('info')}}</div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @endif


    @if(Session::has('activation_required'))
        <div class="alert alert-info">{{session('activation_required')}}</div>
    @endif
    @if(Session::has('activation_failed'))
        <div class="alert alert-danger">{{session('activation_failed')}}</div>
    @endif
    @if(Session::has('registration_complete'))
        <div class="alert alert-success">{{session('registration_complete')}}</div>
    @endif
    @if(Session::has('reset_email_sent'))
        <div class="alert alert-info">{{session('reset_email_sent')}}</div>
    @endif
    @if(Session::has('reset_email_fail'))
        <div class="alert alert-danger">{{session('reset_email_fail')}}</div>
    @endif
    @if(Session::has('already_activated'))
        <div class="alert alert-info">{{session('already_activated')}}</div>
    @endif
    @if(Session::has('password_reset_success'))
        <div class="alert alert-success">{{session('password_reset_success')}}</div>
    @endif
    @if(Session::has('reset_fail'))
        <div class="alert alert-danger">{{session('reset_fail')}}</div>
    @endif
    @if(Session::has('must_activate'))
        <div class="alert alert-danger">{{session('must_activate')}}</div>
    @endif
    @if(Session::has('gateway_pm'))
        <div class="alert alert-danger">{{session('gateway_pm')}}</div>
    @endif
    @if(Session::has('existing_employee_added'))
        <div class="alert alert-success">{{session('existing_employee_added')}}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="header_menu"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <a class="front-logo" href="{{ url('/') }}">
                <img src="{{ static_assets('img/layout/taktyx_logo_1.png') }}"/>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="home-search-form"></div>
            <div class="mb-3 hidden-md-up mobile-search-options">
                <div class="home-search-form-options"></div>
            </div>
            <div class="home-search-results"></div>
        </div>
        <div class="col-md-6 hidden-sm-down">
            <div class="home-search-form-options"></div>
        </div>
    </div>
@endsection
@section('modals')
    <div class="send-takt-popup"></div>
@endsection
