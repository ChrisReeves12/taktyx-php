<?php

namespace App\Http\Middleware;

use App\Contracts\ICustomerAuthService;
use Closure;
use Illuminate\Support\Facades\Session;

class CustAlreadyIn
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
    	if(!$this->customer_auth_service->guest()){
    		Session::flash('error', 'You are already logged in.');
    		return redirect('/');
		}

        return $next($request);
    }
}
