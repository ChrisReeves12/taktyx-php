<?php

namespace App\Http\Controllers\Frontend;

use App\Address;
use App\Contracts\IBusinessAuthService;
use App\Contracts\ICustomerAuthService;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\PhoneNumberRequest;
use App\PhoneNumber;
use App\Services\Repositories\AddressRepository;
use App\Services\Repositories\CatalogBusinessRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\CustomerRepository;
use App\Services\Repositories\ImageRepository;
use App\Services\Repositories\PhoneNumberRepository;
use App\Services\MailerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


/**
 * Class CustomersController
 * @package App\Http\Controllers\Frontend
 */

class CustomersController extends Controller
{
	private $customer_auth_service;
	private $business_auth_service;


	public function __construct(ICustomerAuthService $customer_auth_service, IBusinessAuthService $business_auth_service)
	{
		$this->customer_auth_service = $customer_auth_service;
		$this->business_auth_service = $business_auth_service;
	}

	public function index()
	{
		$customer = $this->customer_auth_service->customer();
		$image = $customer->image;
		return view('frontend.customer_dashboard.index', compact('customer', 'image'));
	}

	public function edit_customer()
	{
		// get the customer
		$customer = $this->customer_auth_service->customer();

		return view('frontend.customer_dashboard.edit_customer', compact('customer'));
	}


	public function create_customer(CustomerRepository $customer_repository)
	{
		// get countries
		$countries = $customer_repository->all();
		return view('frontend.auth.customer.register', compact('countries'));
	}


	public function store_customer(CustomerRequest $request, CustomerRepository $customer_repository, MailerService $mailer)
	{
		$customer = $customer_repository->store($request, $this);

		// log user in
		$this->customer_auth_service->login($customer);

		// send activation email
		$mailer->send_activation_email($customer, $customer->username);
		Session::flash('activation_required', __('An email has been sent with instructions on how to complete your registration.'));

		return redirect('/');
	}


	public function update_customer(CustomerRequest $request, CustomerRepository $customer_repository)
	{
		// get the customer
		$customer = $this->customer_auth_service->customer();

		//update customer
		$customer_repository->update($customer, $request, $this);

		return redirect('customer_dashboard');
	}


	public function edit_address(CountryRepository $country_repository)
	{
		// get countries
		$countries = $country_repository->all('name');

		// get user
		$customer = $this->customer_auth_service->customer();

		// get the address if there is one. Otherwise, just create a new one
		if(!empty($customer->address))
		{
			$address = $customer->address;
		} else {
			$address = new Address;
			// set the default country based either on the phone number or just giving a default
			if(!empty($customer->phone_number)){
				$address->country_id = $customer->phone_number->country_id;
			} else
			{
				$address->country_id = 1;
			}
		}

		return view('frontend.customer_dashboard.edit_address', compact('countries', 'address'));
	}


	public function update_address(Request $request, CustomerRepository $customer_repository,
									AddressRepository $address_repository)
	{
		// get the logged in customer
		$customer = $this->customer_auth_service->customer();

		// save a new address or update existing one
		if(empty($customer) || empty($customer->address))
		{
			// store new address if one isn't already associated with customer
			if(!$address = $address_repository->store($request, $this)){
				Session::flash('geolocator_error',
					'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
				return redirect()->back();
			}

			// associate address with customer
			$customer->address()->associate($address);
			$customer_repository->save($customer);
		}
		else
		{
			// get the existing address and update it
			$address = $customer->address;
			if(!$address_repository->update($address, $request, $this)){
				Session::flash('geolocator_error',
					'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
				return redirect()->back();
			}
		}

		return redirect('/customer_dashboard');
	}


	public function edit_image()
	{
		// get the customer
		$customer = $this->customer_auth_service->customer();

		// get the customer's image.
		$image = $customer->image;

		return view('frontend.customer_dashboard.edit_image', compact('image'));
	}


	public function update_image(Request $request, ImageRepository $image_repository)
	{
		// get the customer
		$customer = $this->customer_auth_service->customer();

		// update the image
		$image = $customer->image;
		$image_repository->update($image, $request, $this);

		return redirect('customer_dashboard');
	}


	public function edit_phone(CountryRepository $country_repository)
	{
		// get the countries
		$countries = $country_repository->all('name');

		// get the logged-in customer
		$customer = $this->customer_auth_service->customer();

		// either create a new number or get the existing one
		if(empty($customer->phone_number)){
			$phone_number = new PhoneNumber;
			// assign default country based either on customer's address, or if he doesn't yet have
			// an address, just pick the default one
			if(!empty($customer->address)){
				$phone_number->country_id = $customer->address->country_id;
			} else {
				$phone_number->country_id = 1;
			}
		} else {
			$phone_number = $customer->phone_number;
		}

		return view('frontend.customer_dashboard.edit_phone_number', compact('customer', 'countries', 'phone_number'));
	}


	public function update_phone(PhoneNumberRequest $request, PhoneNumberRepository $phone_repository, CustomerRepository $customer_repository)
	{
		// gets the customer
		$customer = $this->customer_auth_service->customer();

		// create a new phone number or edits existing one
		if(empty($customer->phone_number))
		{
			$phone_number = $phone_repository->store($request);
			$customer->phone_number()->associate($phone_number);
			$customer_repository->save($customer);
		} else {
			$phone_number = $customer->phone_number;
			$phone_repository->update($phone_number, $request);
		}

		return redirect('customer_dashboard');
	}

	public function all_takts(){

	    // get the customer and the takts sent
	    $customer = $this->customer_auth_service->customer();
	    $takts = $customer->takts()->paginate(15);
	    $name = $customer->username;

	    // get info needed to view selected takts
        $taktable_id = $customer->id;
        $taktable_type = 'customer';

	    return view('frontend.takts.all_takts', compact('name', 'takts', 'taktable_type', 'taktable_id'));
    }

}
