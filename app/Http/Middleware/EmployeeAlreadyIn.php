<?php

namespace App\Http\Middleware;

use App\Contracts\IEmployeeAuthService;
use Closure;

class EmployeeAlreadyIn
{
	protected $employee_auth_service;

	/**
	 * EmployeeAlreadyIn constructor.
	 * @param IEmployeeAuthService $employee_auth_service
	 */
	public function __construct(IEmployeeAuthService $employee_auth_service)
	{
		$this->employee_auth_service = $employee_auth_service;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(!$this->employee_auth_service->non_employee()){
			return redirect()->back();
		}

		return $next($request);
	}
}
