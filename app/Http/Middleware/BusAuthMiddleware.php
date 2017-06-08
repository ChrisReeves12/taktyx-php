<?php

namespace App\Http\Middleware;

use App\Contracts\IBusinessAuthService;
use Closure;

class BusAuthMiddleware
{
	protected $business_auth_service;

	/**
	 * BusActiveMiddleware constructor.
	 * @param IBusinessAuthService $business_auth_service
	 */
	public function __construct(IBusinessAuthService $business_auth_service)
	{
		$this->business_auth_service = $business_auth_service;
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

		// redirect if guest
		if($this->business_auth_service->non_business())
		{
			return redirect('/');
		}

        return $next($request);
    }
}
