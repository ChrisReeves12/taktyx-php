<p>Hi, {{$employee->first_name}}!</p>

<p>{{$service->name}} has added you as an employee. To accept, please click on the following link or<br>
copy it into your browser.</p>

<p>{{route('employee_permit_mailer', ['service_id' => $service->id, 'id' => $employee->id])}}</p>

<p>Best,<br>
The Taktyx Team<br>
https://www.taktyx.com</p>