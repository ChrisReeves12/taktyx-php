<?php
/**
 * The EmployeeAdminGateway class definition.
 *
 * Control access to service replies
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Gateways;

use App\CatalogService;
use App\Contracts\IEmployeeAuthService;
use App\Employee;
use Illuminate\Support\Facades\Session;

/**
 * Class EmployeeAdminGateway
 * @package App\Services\Gateways
 */
class EmployeeAdminGateway
{
	protected $employee_auth_service;
	
	/**
	 * EmployeeAdminGateway constructor.
	 * @param IEmployeeAuthService $employee_auth_service
	 */
	public function __construct(IEmployeeAuthService $employee_auth_service)
	{
		$this->employee_auth_service = $employee_auth_service;
	}

	/**
	 * Control access to employee admin.
	 * @param Employee $employee
	 * @param CatalogService $service
	 * @return bool
	 */
	public function enact($employee, $service)
	{
		$ret_val = true;

		// only allow a business to edit an admin
		if(!$this->employee_auth_service->non_employee())
		{
			if($employee->is_admin($service))
			{
				Session::flash('gateway_pm', 'Admins can only be edited by business owners.');
				$ret_val = false;
			}
		}

		// otherwise, return true
		return $ret_val;
	}
}