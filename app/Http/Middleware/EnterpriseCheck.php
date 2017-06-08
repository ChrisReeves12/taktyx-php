<?php

namespace App\Http\Middleware;

use App\CatalogBusiness;
use App\Contracts\IBusinessAuthService;
use App\Contracts\IEmployeeAuthService;
use Closure;
use Illuminate\Support\Facades\Session;

class EnterpriseCheck
{
	protected $business_auth_service;
	protected $employee_auth_service;

	/**
	 * BusActiveMiddleware constructor.
	 * @param IBusinessAuthService $business_auth_service
	 * @param IEmployeeAuthService $employee_auth_service
	 */
	public function __construct(IBusinessAuthService $business_auth_service, IEmployeeAuthService $employee_auth_service)
	{
		$this->business_auth_service = $business_auth_service;
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
    	// do not allow if not logged in as either a business or an employee
		if(!$this->employee_auth_service->employee() && !$this->business_auth_service->business()){
			Session::flash('error', 'You do not have permission to view this page');
			return redirect('/');
		}

    	// if not logged in as an employee
		if(!$this->employee_auth_service->employee())
		{
			if($this->business_auth_service->non_business())
			{
				Session::flash('error', 'Only enterprise business are allowed to manage employees');
				return redirect('/');
			}

			if($this->business_auth_service->business() instanceof CatalogBusiness)
			{
				if(!$this->business_auth_service->business()->enterprise)
				{
					Session::flash('error', 'Only enterprise business are allowed to manage employees');

					return redirect('/');
				}
			}
		}

        return $next($request);
    }
}
