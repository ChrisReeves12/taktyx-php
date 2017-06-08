<p>Hi, {{$name}}!</p>
<p>We have received a request sent to reset your employee password.<br>
    If you do not remember making this request, please ignore this email.</p>

<p>Please click the link the below to reset your password.</p>

<p>{{route('employee_password_reset', ['key' => $key, 'id' => $id])}}</p>

<p>This link will expire in 10 minutes.</p>

<p>Best,<br>
    The Taktyx Team<br>
    https://www.taktyx.com</p>