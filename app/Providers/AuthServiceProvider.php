<?php

namespace App\Providers;

use App\Contracts\IBusinessAuthService;
use App\Contracts\ICustomerAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Services\BusinessAuthServiceImpl;
use App\Services\CustomerAuthServiceImpl;
use App\Services\EmployeeAuthServiceImpl;
use Illuminate\Support\ServiceProvider;
use View;

class AuthServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// Use view composer to inject auth services into views
		View::composer('frontend.*', function($view) {
			$view->with('customer_auth_service', $this->app->make(ICustomerAuthService::class));
			$view->with('business_auth_service', $this->app->make(IBusinessAuthService::class));
			$view->with('employee_auth_service', $this->app->make(IEmployeeAuthService::class));
		});
	}

	public function register()
	{
		$this->app->singleton(IBusinessAuthService::class, BusinessAuthServiceImpl::class);
		$this->app->singleton(ICustomerAuthService::class, CustomerAuthServiceImpl::class);
		$this->app->singleton(IEmployeeAuthService::class, EmployeeAuthServiceImpl::class);
	}
}
