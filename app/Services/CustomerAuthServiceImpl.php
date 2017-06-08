<?php
/**
 * The CustomerAuthServiceImpl class definition.
 *
 * The default implementation of ICustomerAuthService
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services;

use App\Contracts\ICustomerAuthService;
use App\Customer;
use App\Services\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerAuthServiceImpl
 * @package App\Services
 */
class CustomerAuthServiceImpl extends AbstractAuthService implements ICustomerAuthService
{
	protected $customer;

	/**
	 * CustomerAuthServiceImpl constructor.
	 * @param TokenService $token_service
	 * @param CustomerRepository $repository
	 */
	public function __construct(TokenService $token_service, CustomerRepository $repository)
	{
		parent::__construct($token_service, $repository);
	}

	/**
	 * Saves customer into session after logging in
	 * @param Customer $customer
	 * @param string $session_name
	 * @return Model
	 */
	public function login($customer, $session_name = 'logged_in_customer')
	{
		return parent::login($customer, $session_name);
	}

	/**
	 * Gets the logged in customer
	 * @return Customer
	 */
	public function customer()
	{
		if(empty($this->customer))
			$this->customer = $this->repository->find(session('logged_in_customer'));

		return $this->customer;
	}

	/**
	 * Returns true if user is not logged in
	 * @return bool
	 */
	public function guest()
	{
		$return_val = true;

		// check session to see if user is logged in
		if(session('logged_in_customer') && $customer = $this->repository->find(session('logged_in_customer'))){
			$return_val = false;
		}

		// if there is no session, look for a cookie
		if(!empty($_COOKIE['logged_in_customer'])){

			// locate customer
			if ($customer = $this->repository->find($_COOKIE['logged_in_customer']))
			{
				// check remember digest
				if(cookie('customer_remember_token'))
				{
					//store into session and return false
					session(['logged_in_customer' => $customer->id]);
					$return_val = false;
				}
			}
		}

		return $return_val;
	}

	/**
	 * Logs logged in user out
	 * @param string $session_name
	 * @param string $remember_cookie_name
	 */
	public function logout($session_name = 'logged_in_customer', $remember_cookie_name = 'customer_remember_token')
	{
		parent::logout($session_name, $remember_cookie_name);
	}
}