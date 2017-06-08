<?php
/**
 * The BusinessAuthServiceImpl class definition.
 *
 * The default implementation for IBusinessAuthService
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Services;

use App\CatalogBusiness;
use App\Contracts\IBusinessAuthService;
use App\Services\Repositories\CatalogBusinessRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BusinessAuthServiceImpl
 * @package App\Services
 */
class BusinessAuthServiceImpl extends AbstractAuthService implements IBusinessAuthService
{
	protected $business;

	/**
	 * CustomerAuthServiceImpl constructor.
	 * @param TokenService $token_service
	 * @param CatalogBusinessRepository $repository
	 */
	public function __construct(TokenService $token_service, CatalogBusinessRepository $repository)
	{
		parent::__construct($token_service, $repository);
	}

	/**
	 * Logs the entity in
	 * @param CatalogBusiness $business
	 * @param string $session_name
	 * @return Model
	 */
	public function login($business, $session_name = 'logged_in_business')
	{
		return parent::login($business, $session_name);
	}

	/**
	 * Logs logged in user out
	 * @param string $session_name
	 * @param string $remember_cookie_name
	 */
	public function logout($session_name = 'logged_in_business', $remember_cookie_name = 'business_remember_token')
	{
		parent::logout($session_name, $remember_cookie_name);
	}

	/**
	 * Gets the currently logged in business
	 * @return CatalogBusiness
	 */
	public function business()
	{
		if(empty($this->business))
			$this->business = $this->repository->find(session('logged_in_business'));

		return $this->business;
	}

	/**
	 * Returns true if user is not logged in as a business
	 * @return bool
	 */
	public function non_business(): bool
	{
		$return_val = true;

		// check session to see if business is logged in
		if(session('logged_in_business') && $business = $this->repository->find(session('logged_in_business'))){
			$return_val = false;
		}

		// if there is no session, look for a cookie
		if(!empty($_COOKIE['logged_in_business'])){

			// locate business
			if ($business = $this->repository->find($_COOKIE['logged_in_business']))
			{
				// check remember digest
				if(cookie('business_remember_token'))
				{
					//store into session and return false
					session(['logged_in_business' => $business->id]);
					$return_val = false;
				}
			}
		}

		return $return_val;
	}
}