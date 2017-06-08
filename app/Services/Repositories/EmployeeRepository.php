<?php
/**
 * The EmployeeRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\CatalogService;
use App\Contracts\IRepository;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;

/**
 * Class EmployeeRepository
 * @package App\Services\Repositories
 */
class EmployeeRepository extends BaseRepositoryImpl implements IRepository
{
	private $cat_service_repository;
	private $image_repository;

	/**
	 * EmployeeRepository constructor.
	 * @param CatalogServiceRepository $cat_service_repository
	 * @param ImageRepository $image_repository
	 */
	public function __construct(CatalogServiceRepository $cat_service_repository, ImageRepository $image_repository)
	{
		$this->cat_service_repository = $cat_service_repository;
		$this->image_repository = $image_repository;
		$this->setClass(Employee::class);
	}

	/**
	 * Save employee
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Employee
	 */
	public function store($request, $foreign_key = null)
	{
		// form validations
		$this->_validate_class();
		$this->_validate_record($request->all());

		// create new employee
		$employee = new Employee([
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);

		// save employee
		$this->save($employee);

		// return the employee in case it's needed
		return $employee;
	}

	/**
	 * Update employee
	 * @param Employee $employee
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Employee
	 */
	public function update($employee, $request, $foreign_key = null)
	{
		// validate first and last name
		Validator::make($request->all(), [
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50'
		])->validate();

		// validate email if it is changed
		if($request->email != $employee->email)
		{
			Validator::make($request->all(), [
				'email' => 'required|max:100|email|unique:employees'
			])->validate();
		}

		// validate password if it is changed
		if(!empty($request->password) || !empty($request->password_confirmation)){
			Validator::make($request->all(), [
				'password' => 'required|min:7|alpha_num|confirmed'
			])->validate();
		}

		// update the employee
		$employee->first_name = $request->first_name;
		$employee->last_name = $request->last_name;

		// if email is changed, change email
		if($request->email != $employee->email)
		{
			$employee->email = $request->email;
		}

		// if password is changed, change password
		if(!empty($request->password))
		{
			$employee->password = bcrypt($request->password);
		}

		// save the employee
		$this->save($employee);

		return $employee;
	}

	/**
	 * Delete employee from the given service
	 * @param Employee $employee
	 * @param CatalogService $service
	 */
	public function deleteFromService($employee, $service)
	{
		// disassociate employee
		$service->employees()->detach($employee);

		// if employee isn't employed by anyone else, remove the record
		if(count($employee->services) < 1)
		{
			$employee->delete();
		}
	}

	/**
	 * Get all employees on given service
	 * @param int $service_id
	 * @param bool $paginate
	 * @return Collection
	 */
	public function allFromService($service_id, $paginate = true)
	{
		if($paginate == true)
		{
			$ret_val = $this->cat_service_repository->find($service_id)->employees()->paginate(15);
		}
		else
		{
			$ret_val = $this->cat_service_repository->find($service_id)->employees()->get();
		}

		return $ret_val;
	}
}