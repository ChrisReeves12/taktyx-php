<?php

namespace App\Providers;

use App\Contracts\IRepository;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CustomersController;
use App\Http\Controllers\Frontend\EmployeesController;
use App\Services\Repositories\CustomerRepository;
use App\Services\Repositories\EmployeeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
		// CustomersController uses Customer repository
		$this->app->when(CustomersController::class)->needs(IRepository::class)->give(CustomerRepository::class);

    	// Auth Controller uses Customer repository
		$this->app->when(AuthController::class)->needs(IRepository::class)->give(CustomerRepository::class);

		// Employee controller uses Employee repository
		$this->app->when(EmployeesController::class)->needs(IRepository::class)->give(EmployeeRepository::class);
    }
}
