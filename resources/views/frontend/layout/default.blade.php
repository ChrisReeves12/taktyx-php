<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title>Taktyx</title>
    <link rel="icon" href="{{ static_assets('img/layout/favicon.png') }}" type="image/png"/>
    <link href="{{ static_assets('public/css/frontend/combined.min.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<header>
    <div class="top-nav row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6 user-account-menu-section">
            <div class="user-account-menu">
                <ul>
                    <li><a href="{{route('frontend_home_index')}}">HOME</a></li>
                    @if($customer_auth_service->guest())
                        <li><a href="{{route('frontend_cust_login')}}">Sign In</a></li>
                        <li><a href="{{route('frontend_auth_register')}}">Register</a></li>
                    @else
                        <li><a href="{{route('customer_dashboard')}}">Welcome, {{ $customer_auth_service->customer()->username }}!</a></li>
                        <li><a href="{{route('logout_user')}}">Log Out</a></li>
                        @if($customer_auth_service->customer()->status == 'inactive')
                            <li><a href="{{route('resend_activation')}}">Resend Activation Email</a></li>
                        @endif
                    @endif
                    <li><a href="{{route('business_index')}}">Business</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<section class="container" id="main_content">
    @yield('content')
</section>
@yield('modals')
<script>
    window.taktyx = window.taktyx || {};
    window.taktyx.csrf = "{{ csrf_token() }}";
    window.taktyx.env = "{{ env('APP_ENV') }}";
    window.taktyx.assets_path = "{{ config('general.frontend_static_path') }}";
    window.taktyx.react = window.taktyx.react || {};
</script>
@yield('js-globals')
<script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<script src="{{ static_assets('public/js/frontend/combined.min.js') }}"></script>
</body>
</html>