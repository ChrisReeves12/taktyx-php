<?php

namespace App\Http\Controllers\Frontend;

use App\Address;
use App\Contracts\IBusinessAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Contracts\IRepository;
use App\Http\Controllers\Controller;
use App\Image;
use App\PhoneNumber;
use App\Services\Gateways\EmployeeAdminGateway;
use App\Services\Gateways\EmployeeGateway;
use App\Services\Repositories\AddressRepository;
use App\Services\Repositories\CatalogServiceRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\EmployeeRepository;
use App\Services\Repositories\ImageRepository;
use App\Services\Repositories\PhoneNumberRepository;
use App\Services\MailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Class EmployeesController
 * @package App\Http\Controllers\Frontend
 */

class EmployeesController extends Controller
{

	public $repository;
	public $service_repository;
	public $employee_gateway;
	public $employee_admin_gateway;
	public $business_auth_service;
	public $employee_auth_service;

	public function __construct(IRepository $repository, CatalogServiceRepository $service_repository,
								EmployeeGateway $employee_gateway, EmployeeAdminGateway $employee_admin_gateway,
								IBusinessAuthService $business_auth_service, IEmployeeAuthService $employee_auth_service){
		$this->repository = $repository;
		$this->service_repository = $service_repository;
		$this->employee_gateway = $employee_gateway;
		$this->employee_admin_gateway = $employee_admin_gateway;
		$this->business_auth_service = $business_auth_service;
		$this->employee_auth_service = $employee_auth_service;
	}

	public function index($service_id){

		// get the service repository
		$service = $this->service_repository->find($service_id);

		// get all employees
		$employees = $this->repository->allFromService($service_id);
    	return view('frontend.catalog_business.catalog_service.employees.manage_employees.index', compact('service', 'employees'));
	}

	public function create($service_id)
	{
		// get the service
		$service = $this->service_repository->find($service_id);

		return view('frontend.catalog_business.catalog_service.employees.manage_employees.create', compact('service'));
	}

	public function store($service_id, Request $request, ImageRepository $image_repository){

		// get the service
		$service = $this->service_repository->find($service_id);

		// if email already exists, then we need to email the existing employee with a letter to
		// approve of becoming an employee of your service. But don't allow storing if employee is
		// already added.
		if($existing_employee = $this->repository->find_by('email', $request->email)){
			// make sure employee isn't already an employee
			if(!$service->employees()->where('email', $existing_employee->email)->first())
			{
				return redirect()->route('existing_employee_mailer_form', ['service_id' => $service->id, 'id' => $existing_employee->id]);
			} else {
				// employee already exists
				Session::flash('employee_exists_already', 'There is already an employee of this service with this email address.');
				return redirect("/business/service/{$service->id}/employees");
			}
		}

		// create employee and save
		$employee = $this->repository->store($request, $this);

		// associate employee to service
		$service->employees()->save($employee);

		// create default image for employee
		$image = new Image(['path' => $image_repository->generic('employee')]);
		$image_repository->save($image);

		// update table
		DB::table('catalog_service_employee')
			->where([['catalog_service_id', $service->id], ['employee_id', $employee->id] ])
			->update(['image_id' => $image->id]);

		return redirect("business/service/{$service->id}/employees");
	}

	public function show($service_id, $id){

	}

	public function existing_employee_mailer_form($service_id, $id){
		$employee = $this->repository->find($id);
		$service = $this->service_repository->find($service_id);
		if($this->employee_gateway->enact($employee, $service))
		{
			return view('frontend.catalog_business.catalog_service.employees.manage_employees.mailer_option', compact('employee', 'service'));
		} else {
			// user is forbidden
			return redirect('/');
		}
	}

	public function employee_mailer_send($service_id, $id, Request $request, MailerService $mailer){

		$employee = $this->repository->find($id);
		$service = $this->service_repository->find($service_id);

		if($request->mailer_choice == "yes")
		{
			$mailer->send_mail('noreply@taktyx.com', 'Taktyx Admin',
				$employee, 'Employee Request Sent', 'mail.employee_permission', ['employee' => $employee, 'service' => $service]);
			Session::flash('employee_permission_sent', 'An email to this employee has been sent with instructions on how to accept your request.');

			return redirect("/business/service/{$service->id}/employees");
		} else {
			return redirect("/business/service/{$service->id}/employees/new");
		}
	}

	public function employee_permit($service_id, $id){
		$service = $this->service_repository->find($service_id);
		$employee = $this->repository->find($id);
		return view('frontend.catalog_business.catalog_service.employees.manage_employees.employee_permit_verify',
			compact('service', 'employee'));
	}

	public function employee_permit_verify($service_id, $id, Request $request, ImageRepository $image_repository){
		// get the service
		$service = $this->service_repository->find($service_id);
		// verify email
		if($employee = $this->repository->find_by('email', $request->email)){
			// verify password
			if(Hash::check($request->password, $employee->password)){

				// add employee to service
				$service->employees()->save($employee);

				// give default image
				$image = new Image(['path' => 'genericprofile.jpg']);
				$image_repository->save($image);
				DB::table('catalog_service_employee')
					->where([['catalog_service_id', $service->id], ['employee_id', $employee->id]])
					->update(['image_id' => $image->id]);

				Session::flash('existing_employee_added',
					"Your employee account has been successfully registered to {$service->name}.");
				return redirect('/');
			} else {
				// password is incorrect
				Session::flash('error', 'Your password for this account is incorrect. Please try again.');
				return redirect()->back();
			}
		} else {
			// email couldn't be found
			Session::flash('error', 'We could not find an employee account by that email. Please try again.');
			return redirect()->back();
		}
	}

	public function edit($service_id, $id){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// make sure only those who have permission can access
		if($this->employee_gateway->enact($employee, $service))
		{
			if($this->employee_admin_gateway->enact($employee, $service))
			{
				// send to page
				return view('frontend.catalog_business.catalog_service.employees.manage_employees.edit', compact('service', 'employee'));
			} else {
				// redirect to home page
				return redirect('/');
			}
		}
	}

	public function edit_account($service_id, $id){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// make sure only employee has access
		if($this->employee_auth_service->employee() == $employee)
		{
			// send to account edit
			return view('frontend.catalog_business.catalog_service.employees.employee_user.edit_account', compact('employee', 'service'));
		} else {
			// redirect because business isn't allowed
			return redirect('/');
		}
	}

	public function edit_address($service_id, $id, CountryRepository $country_repository){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		if($this->employee_gateway->enact($employee, $service)){
			if($this->employee_admin_gateway->enact($employee, $service))
			{
				// get the country
				$countries = $country_repository->all('name');

				// get the address if new. get it if not
				if(empty($employee->address($service)))
				{
					$address = new Address(['country_id' => 1]);
				}
				else
				{
					$address = $employee->address($service);
				}

				return view('frontend.catalog_business.catalog_service.employees.manage_employees.edit_address', compact('employee', 'service', 'countries', 'address'));
			} else {
				// business isn't allowed
				return redirect('/');
			}
		} else {
			// businesss isn't allowed
			return redirect('/');
		}
	}

	public function change_admin($service_id, $id){

		// get the service and employee
		$service = $this->service_repository->find($service_id);
		$employee = $this->repository->find($id);

		// change the status
		if($employee->is_admin($service)){
			$employee->revoke_admin($service);
		} else {
			$employee->make_admin($service);
		}

		return redirect("/business/service/{$service->id}/employees");
	}

	public function update_address($service_id, $id, AddressRepository $address_repository, Request $request){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// only allow business or employee with permission
		if($this->employee_gateway->enact($employee, $service)){
			if($this->employee_admin_gateway->enact($employee, $service))
			{

				// save the address
				if(empty($employee->address($service)))
				{
					if($address = $address_repository->store($request, $this))
					{
						// update table
						DB::table('catalog_service_employee')->where([['employee_id', $employee->id], ['catalog_service_id', $service->id]])->update(['address_id' => $address->id]);

						return redirect("business/service/{$service->id}/employees/{$employee->id}/edit");
					}
					else
					{
						Session::flash('geolocator_error', 'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');

						return redirect()->back();
					}
				}
				else
				{
					$address = $employee->address($service);
					if($address_repository->update($address, $request, $this))
					{
						return redirect("business/service/{$service->id}/employees/{$employee->id}/edit");
					}
					else
					{
						Session::flash('geolocator_error', 'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
						return redirect()->back();
					}
				}
			} else {
				// admin trying to edit admin
				return redirect('/');
			}
		} else {
			// business is not allowed
			return redirect('/');
		}
	}

	public function edit_phone($service_id, $id, CountryRepository $country_repository){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// verify business or employee has permission
		if($this->employee_gateway->enact($employee, $service))
		{
			if($this->employee_admin_gateway->enact($employee, $service))
			{
				// get the phone number
				if(empty($employee->phone_number($service)))
				{
					$phone_number = new PhoneNumber(['country_id' => 1]);
				}
				else
				{
					$phone_number = $employee->phone_number($service);
				}

				// get the countries
				$countries = $country_repository->all('name');

				return view('frontend.catalog_business.catalog_service.employees.manage_employees.edit_phone_number', compact('countries', 'phone_number', 'employee', 'service'));
			} else {
				// admin trying to edit admin
				return redirect('/');
			}
		} else {
			// business not allowed
			return redirect('/');
		}
	}

	public function update_phone($service_id, $id, Request $request, PhoneNumberRepository $phone_repository){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// verify permission
		if($this->employee_gateway->enact($employee, $service))
		{
			if($this->employee_admin_gateway->enact($employee, $service))
			{
				// get the phone number and save it, then associate with employee
				if(empty($employee->phone_number($service)))
				{
					$phone_number = $phone_repository->store($request, $this);

					// update table
					DB::table('catalog_service_employee')->where([['employee_id', $employee->id],
						['catalog_service_id', $service->id]])->update(['phone_number_id' => $phone_number->id]);
				}
				else
				{
					$phone_number = $employee->phone_number($service);
					$phone_repository->update($phone_number, $request, $this);
				}
			} else {
				// admin trying to edit an admin
				return redirect('/');
			}
			return redirect("business/service/{$service->id}/employees/{$employee->id}/edit");
		} else {
			// business isn't allowed
			return redirect('/');
		}
	}

	public function edit_image($service_id, $id){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// make sure permission is granted
		if($this->employee_gateway->enact($employee, $service))
		{
			// get the image
			$image = $employee->image($service);

			return view('frontend.catalog_business.catalog_service.employees.manage_employees.edit_image',
				compact('image', 'employee', 'service'));
		} else {
			//user not allowed
			return redirect('/');
		}
	}

	public function update($service_id, $id, Request $request){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get the employee
		$employee = $this->repository->find($id);

		// make sure permission is granted
		if($this->employee_gateway->enact($employee, $service))
		{
			// update the employee
			$this->repository->update($employee, $request, $this);

			// go back to either employee dashboard or employee editor, depending on who is logged in
			return $this->_employee_goback($service, $employee);
		} else {
			// user isn't allowed
			return redirect('/');
		}
	}

	public function update_image($service_id, $id, Request $request, ImageRepository $image_repository){

		// get the service
		$service = $this->service_repository->find($service_id);

		// get employee
		$employee = $this->repository->find($id);

		// make sure permission is granted
		if($this->employee_gateway->enact($employee, $service))
		{
			// get image
			$image = $employee->image($service);

			// save the image
			$image_repository->update($image, $request, $this);

			// go back to either employee dashboard or employee editor, depending on who is logged in
			return $this->_employee_goback($service, $employee);
		} else {
			// user isn't allowed
			return redirect('/');
		}
	}

	public function delete($service_id, $id, EmployeeRepository $employee_repository){

		$business = $this->business_auth_service->business();
		$service = $this->service_repository->find($service_id);
		$employee = $this->repository->find($id);

		// verify permission
		if($service->business == $business){
			// delete employee
			$employee_repository->deleteFromService($employee, $service);
			return redirect("business/service/{$service_id}/employees");
		} else {
			// not allowed to delete
			return redirect('/');
		}
	}

	public function select_service(){

		// get the employee and services
		$employee = $this->employee_auth_service->employee();
		$services = $employee->services;

		return view('frontend.catalog_business.catalog_service.employees.employee_user.select_service',
			compact('employee', 'services'));
	}

	public function select_service_do(Request $request){
		$employee = $this->employee_auth_service->employee();
		return redirect()->route('employee_dashboard', ['service_id' => $request->select_service, 'id' => $employee->id]);
	}

	public function employee_dashboard($service_id){

		// get the employee
		$employee = $this->employee_auth_service->employee();

		// get the services the user is an employee of
		$services = $employee->services;

		// set the service to focus on if user is an employee of more than one service
		$current_service = $employee->services()->where('catalog_service_id', $service_id)->first();

		return view('frontend.catalog_business.catalog_service.employees.employee_user.dashboard',
						compact('employee', 'services', 'current_service'));
	}

	public function admin_manage_employees($service_id, CatalogServiceRepository $service_repository){

		// get the service and employee
		$service = $service_repository->find($service_id);
		$admin = $this->employee_auth_service->employee();
		$employees = $service->employees;

		// verify permissions
		if($admin->is_admin($service)){
			return view('frontend.catalog_business.catalog_service.employees.manage_employees.index',
				compact('service', 'employees', 'admin'));
		} else {
			Session::flash('error', 'You do not have permission to view this page.');
			return redirect('/');
		}
	}

	private function _employee_goback($service, $employee){
		// if logged in as an employee
		if($this->employee_auth_service->employee()){
			return redirect("/business/service/{$service->id}/employees/{$employee->id}/dashboard");
		} elseif ($this->business_auth_service->business()){
			return redirect("business/service/{$service->id}/employees/{$employee->id}/edit");
		}
	}

}
