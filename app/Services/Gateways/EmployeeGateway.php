<?php
/**
 * The CustomerReplyGateway class definition.
 *
 * Control access to service replies
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Gateways;

use App\CatalogService;
use App\Contracts\IBusinessAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Employee;
use Illuminate\Support\Facades\Session;

/**
 * Class EmployeeGateway
 * @package App\Services\Gateways
 */
class EmployeeGateway
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
	 * Control access to employee.
	 * @param Employee $employee
	 * @param CatalogService $service
	 * @return bool
	 */
	public function enact($employee, $service)
	{
		$ret_val = true;

		// prevent a business who isn't allowed to edit this from doing so
		if(!$this->business_auth_service->non_business()){

			if($this->business_auth_service->business() != $service->business){
				Session::flash('gateway_pm', 'You do not have permission to edit this employee.');
				$ret_val = false;
			}
		}

		// prevent if employee doesn't have permission
		elseif(!$this->employee_auth_service->non_employee()){
			// check if admin
			if($this->employee_auth_service->employee()->is_admin($service) == false){
				// check if logged in employee matches employee
				if($this->employee_auth_service->employee() != $employee){
					Session::flash('gateway_pm', 'You do not have permission to edit this employee.');
					$ret_val = false;
				}
			}
		}

		// don't allow somebody who isn't even logged in
		elseif($this->employee_auth_service->non_employee() && $this->business_auth_service->non_business()){
			Session::flash('gateway_pm', 'You do not have permission to edit this employee.');
			$ret_val = false;
		}


		// otherwise, return true
		return $ret_val;
	}
}