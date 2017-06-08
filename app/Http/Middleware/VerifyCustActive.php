<?php

namespace App\Http\Middleware;

use App\Contracts\ICustomerAuthService;
use App\Customer;
use Closure;
use Illuminate\Support\Facades\Session;

class VerifyCustActive
{
	private $customer_auth_service;

	/**
	 * CustAlreadyIn constructor.
	 * @param ICustomerAuthService $customer_auth_service
	 */
	public function __construct(ICustomerAuthService $customer_auth_service)
	{
		$this->customer_auth_service = $customer_auth_service;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
    public function handle($request, Closure $next)
    {

		// redirect if guest
		if($this->customer_auth_service->guest())
		{
			return redirect('/');
		}

		// redirect if status is inactive
		if($this->customer_auth_service->customer() instanceof Customer)
		{
			if($this->customer_auth_service->customer()->status == 'inactive')
			{
				Session::flash('must_activate', 'You must activate your account before proceeding.');
				return redirect('/');
			}
		}

        return $next($request);
    }
}
