@extends('frontend.layout.default')
@section('content')

    <h1>Create Employee</h1>
    <hr>
    An employee with the email {{$employee->email}} already exists. To make this user an employee, an email<br>
    must be sent for the user's approval. Would you like to do that now?
    <form method="post" action="{{route('employee_mailer_send', ['service_id' => $service->id, 'id' => $employee->id])}}">
        {{csrf_field()}}
        <div class="form-group">
            <select class="form-control" name="mailer_choice">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="submit">
    </form>

@endsection