<?php
/**
 * The AuthServiceImpl class definition.
 *
 * Default implementation of the IAuthService contract
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services;

use App\Contracts\IAuthService;
use App\Contracts\IRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Hash;
use Session;

/**
 * Class AuthServiceImpl
 * @package App\Services
 */
abstract class AbstractAuthService implements IAuthService
{
	protected $token_service;
	protected $repository;

	/**
	 * AuthServiceImpl constructor.
	 * @param TokenService $token_service
	 * @param IRepository $repository
	 */
	public function __construct(TokenService $token_service, IRepository $repository)
	{
		$this->token_service = $token_service;
		$this->repository = $repository;
	}

	/**
	 * Verify record by email address
	 * @param Request $request
	 * @return bool|Model
	 */
	public function verify($request)
	{
		// get info
		$in_email = $request['email'];
		$in_password = $request['password'];

		// locate user by email
		if($verifiable = $this->repository->find_by('email', $in_email))
		{
			// verify password
			if(Hash::check($in_password, $verifiable->password))
			{
				$return_val = $verifiable;
			}
		}

		return $return_val ?? false;
	}

	/**
	 * Verify the record's password reset
	 * @param int $id
	 * @param string $key
	 * @return bool|Model
	 */
	public function verify_password_reset($id, $key)
	{
		// locate and verify checkable entity
		if($checkable = $this->repository->find($id)){
			if(Hash::check($key, $checkable->reset_digest)){
				if(Carbon::now() <= $checkable->reset_digest_expire){
					$return_val = $checkable;
				}
			}
		}

		return $return_val ?? false;
	}

	/**
	 * Activates the record's account
	 * @param int $id
	 * @param string $key
	 * @return Model|bool
	 */
	public function activate($id, $key)
	{
		$return_val = false;

		// get the customer
		if($activatable = $this->repository->find($id))
		{
			if(Hash::check($key, $activatable->activation_digest))
			{
				if($activatable->status == 'inactive')
				{
					Session::flash('registration_complete', __('Your Taktyx account has been activated!'));
					$activatable->status = "active";
					$this->repository->save($activatable);
					$return_val = $activatable;
				}
				elseif($activatable->status == 'active')
				{
					Session::flash('already_activated', __('This Taktyx account is already active.'));
				}
			}
		}

		// if activation fails, send error message
		if(!$return_val)
			Session::flash('activation_failed', __('Activation failed.'));

		return $return_val;
	}

	/**
	 * Change password of the record
	 * @param Model $changeable
	 * @param Request $request
	 * @return Model
	 */
	public function change_password($changeable, $request)
	{
		// change password
		$changeable->password = bcrypt($request->password);

		// remove reset digest
		$changeable->reset_digest = null;
		$changeable->reset_digest_expire = null;

		$this->repository->save($changeable);

		return $changeable;
	}

	/**
	 * Set cookie to remember record on login
	 * @param $memorable
	 * @return Model
	 */
	public function remember($memorable)
	{
		// create tokens
		$keys = $this->token_service->create();
		$token = $keys['key'];
		$digest = $keys['key_encoded'];

		// store user id and remember token in cookies
		$memorable->remember_digest = $digest;
		$expire = time() + (60*24*365*20); // 20 years, as in this cookie lasts forever
		setCookie('logged_in_customer', $memorable->id, $expire);
		setCookie('customer_remember_token', $token, $expire);

		// save remember digest
		$this->repository->save($memorable);

		return $memorable;
	}

	/**
	 * Logs the entity in
	 * @param Model $record
	 * @param string $session_name
	 * @return Model
	 */
	public function login($record, $session_name = '')
	{
		// save into session
		session([$session_name => $record->id]);
		return $record;
	}

	/**
	 * Log the entity out
	 * @param string $session_name
	 * @param string $remember_cookie_name
	 */
	public function logout($session_name = '', $remember_cookie_name = '')
	{
		session([$session_name => null]);

		// get rid of remember cookies...
		if(cookie($session_name)){
			setCookie($session_name, 0, time() - 3000);
		}
		if(cookie($remember_cookie_name)){
			setCookie($remember_cookie_name, null, time() - 3000);
		}
	}

	/**
	 * @param IRepository $repository
	 */
	public function setRepository($repository)
	{
		$this->repository = $repository;
	}
}