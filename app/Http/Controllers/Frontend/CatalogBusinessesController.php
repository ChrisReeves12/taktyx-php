<?php
/**
 * The CatalogBusinessesController class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Http\Controllers\Frontend;

use App\Address;
use App\Contracts\IBusinessAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Http\Controllers\Controller;
use App\PhoneNumber;
use App\Services\Repositories\AddressRepository;
use App\Services\Repositories\CatalogBusinessRepository;
use App\Services\Repositories\CatalogServiceRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\PhoneNumberRepository;
use App\Services\MailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class CustomersController
 * @package App\Http\Controllers\Frontend
 */
class CatalogBusinessesController extends Controller
{
	private $business_repository;
	private $country_repository;
	private $business_auth_service;
	private $employee_auth_service;
	private $service_repository;

	/**
	 * CatalogBusinessesController constructor.
	 * @param CatalogBusinessRepository $business_repository
	 * @param CatalogServiceRepository $service_repository
	 * @param CountryRepository $country_repository
	 * @param IBusinessAuthService $business_auth_service
	 * @param IEmployeeAuthService $employee_auth_service
	 */
	public function __construct(CatalogBusinessRepository $business_repository,
								CatalogServiceRepository $service_repository,
								CountryRepository $country_repository,
								IBusinessAuthService $business_auth_service,
								IEmployeeAuthService $employee_auth_service)
	{
		$this->business_repository = $business_repository;
		$this->service_repository = $service_repository;
		$this->country_repository = $country_repository;
		$this->business_auth_service = $business_auth_service;
		$this->employee_auth_service = $employee_auth_service;
	}

	/**
	 * Home screen for business profile
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function index()
	{
		$services = [];

		// get the business and services
		if($this->business_auth_service->non_business())
		{
			// if logged in as an employee, go to employee select service instead
			if(!$this->employee_auth_service->non_employee()){
				$employee = $this->employee_auth_service->employee();
				$ret_val = redirect()->route('employee_select_service');
			}
			else
			{
				// otherwise, just simply to to the business index
				$ret_val = view('frontend.catalog_business.index', ['business_auth_service' => $this->business_auth_service]);
			}
		}
		else
		{
			$business = $this->business_auth_service->business();
			$services = $business->services;
		}

		if(empty($ret_val))
			$ret_val = view('frontend.catalog_business.index', compact('services'));

		return $ret_val;
	}

	/**
	 * Business registration screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
    	return view('frontend.auth.business.register_business');
	}

	/**
	 * Save new business registration
	 * @param Request $request
	 * @param MailerService $mailer
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(Request $request, MailerService $mailer)
	{
		$business = $this->business_repository->store($request, $this);
		$this->business_auth_service->login($business);
		$mailer->send_activation_email($business, $business->name, 'mail.business_activation_email', 'Taktyx Business Registration');
		Session::flash('business_activation_sent', 'An email has been sent to your email account to activate your business account.');
		return redirect('/business');
	}

	/**
	 * Business dashboard screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function business_dashboard()
	{
		$business = $this->business_auth_service->business();
		return view('frontend.business_dashboard.index', compact('business'));
	}

	/**
	 * Edit business form
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit_business()
	{
		$business = $this->business_auth_service->business();
		return view('frontend.business_dashboard.edit_business', compact('business'));
	}

	/**
	 * Do updates to business
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update_business(Request $request)
	{
		$business = $this->business_auth_service->business();
		$this->business_repository->update($business, $request, $this);
		return redirect('/business_dashboard');
	}

	/**
	 * Edit address form
	 * @param CountryRepository $country_repository
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit_address(CountryRepository $country_repository)
	{
		// get countries
		$countries = $country_repository->all('name');

		// get business
		$business = $this->business_auth_service->business();

		// get the address if there is one. Otherwise, just create a new one
		if(!empty($business->address))
		{
			$address = $business->address;
		}
		else
		{
			$address = new Address;

			// set the default country based either on the phone number or just giving a default
			if(!empty($business->phone_number))
			{
				$address->country_id = $business->phone_number->country_id;
			}
			else
			{
				$address->country_id = 1;
			}
		}

		return view('frontend.business_dashboard.edit_address', compact('countries', 'address'));
	}

	/**
	 * Do update to address
	 * @param Request $request
	 * @param AddressRepository $address_repository
	 * @param CatalogBusinessRepository $business_repository
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update_address(Request $request, AddressRepository $address_repository,
								   CatalogBusinessRepository $business_repository)
	{
		// get the business
		$business = $this->business_auth_service->business();

		// store address or update
		if(empty($business->address))
		{
			// store address but display error and go back if there is an error
			if(!$address = $address_repository->store($request, $this))
			{
				Session::flash('geolocator_error',
					'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
				$ret_val = redirect()->back();
			}
		}
		else
		{
			// update address but display error and go back if there is an error
			if(!$business->address = $address_repository->update($business->address, $request, $this))
			{
				Session::flash('geolocator_error',
					'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
				$ret_val = redirect()->back();
			}

			// if all went smoothly, return to business dashboard
			$ret_val = redirect('business_dashboard');
		}

		// attach address to business
		if(!empty($address))
			$business->address()->associate($address);

		$business_repository->save($business);

		return $ret_val ?? redirect('/business_dashboard');
	}

	/**
	 * Edit phone number screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit_phone()
	{
		// get the business
		$business = $this->business_auth_service->business();
		$countries = $this->country_repository->all('name');

		// get the phone number or create a new one
		if(empty($business->phone_number)){
			$phone_number = new PhoneNumber(['country_id' => 1]);
		} else {
			$phone_number = $business->phone_number;
		}
		return view('frontend.business_dashboard.edit_phone', compact('phone_number', 'countries', 'business'));
	}

	/**
	 * Do update phone number
	 * @param Request $request
	 * @param PhoneNumberRepository $phone_number_repository
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update_phone(Request $request, PhoneNumberRepository $phone_number_repository)
	{
		// get the business
		$business = $this->business_auth_service->business();

		// get the phone number or create a new one
		if(empty($business->phone_number)){
			$phone_number = $phone_number_repository->store($request, $this);
			$business->phone_number()->associate($phone_number);
			$this->business_repository->save($business);
		} else {
			$phone_number = $business->phone_number;
			$phone_number_repository->update($phone_number, $request, $this);
		}

		return redirect('/business_dashboard');
	}
}
