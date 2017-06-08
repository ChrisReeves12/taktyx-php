<?php
/**
 * The EmployeeAuthImpl class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services;

use App\Contracts\IEmployeeAuthService;
use App\Employee;
use App\Services\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmployeeAuthImpl
 * @package App\Services
 */
class EmployeeAuthServiceImpl extends AbstractAuthService implements IEmployeeAuthService
{
	protected $employee;

	/**
	 * CustomerAuthServiceImpl constructor.
	 * @param TokenService $token_service
	 * @param EmployeeRepository $repository
	 */
	public function __construct(TokenService $token_service, EmployeeRepository $repository)
	{
		parent::__construct($token_service, $repository);
	}

	/**
	 * Logs the entity in
	 * @param Employee $employee
	 * @param string $session_name
	 * @return Model
	 */
	public function login($employee, $session_name = 'logged_in_employee')
	{
		return parent::login($employee, $session_name);
	}

	/**
	 * Logs logged in user out
	 * @param string $session_name
	 * @param string $remember_cookie_name
	 */
	public function logout($session_name = 'logged_in_employee', $remember_cookie_name = 'employee_remember_token')
	{
		parent::logout($session_name, $remember_cookie_name);
	}

	/**
	 * Gets the currently logged in employee
	 * @return Employee
	 */
	public function employee()
	{
		if(empty($this->employee))
			$this->employee = $this->repository->find(session('logged_in_employee'));

		return $this->employee;
	}

	/**
	 * Returns true if user is not logged in as employee
	 * @return bool
	 */
	public function non_employee(): bool
	{
		$return_val = true;

		// check session to see if employee is logged in
		if(session('logged_in_employee') && $employee = $this->repository->find(session('logged_in_employee'))){
			$return_val = false;
		}

		// if there is no session, look for a cookie
		if(!empty($_COOKIE['logged_in_business'])){

			// locate employee
			if ($business = $this->repository->find($_COOKIE['logged_in_employee']))
			{
				// check remember digest
				if(cookie('employee_remember_token'))
				{
					//store into session and return false
					session(['logged_in_employee' => $business->id]);
					$return_val = false;
				}
			}
		}

		return $return_val;
	}
}