<?php
/**
 * The IEmployeeAuthService class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Contracts;

use App\Employee;

/**
 * Interface IEmployeeAuthService
 * @package App\Contracts
 */
interface IEmployeeAuthService extends IAuthService
{
	/**
	 * Gets the currently logged in employee
	 * @return Employee
	 */
	public function employee();

	/**
	 * Returns true if user is not logged in as employee
	 * @return bool
	 */
	public function non_employee(): bool;
}