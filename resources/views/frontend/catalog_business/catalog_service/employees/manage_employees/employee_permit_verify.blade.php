@extends('frontend.layout.default')
@section('content')

    <h1>Taktyx Employee Permit</h1>
    <hr>
    <p>Please verify your employee account by inputting your email and password.</p><br>

    <!-- Flash messages -->
    @if(Session::has('error'))
        <div class="alert alert-danger">@lang(session('error'))</div>
    @endif

    <form method="post" action="{{route('employee_permit_verify', ['service_id' => $service->id, 'id' => $employee->id])}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" value="{{old('email')}}" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <input type="submit" class="btn btn-primary" name="submit">
    </form>

@endsection
