<?php
/**
 * The IAuthService interface definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface IAuthService
 * @package App\Contracts
 */
interface IAuthService
{
	/**
	 * Verify record by email address
	 * @param Request $request
	 * @return bool|Model
	 */
	public function verify($request);

	/**
	 * Verify the record's password reset
	 * @param int $id
	 * @param string $key
	 * @return bool|Model
	 */
	public function verify_password_reset($id, $key);

	/**
	 * Activates the record's account
	 * @param int $id
	 * @param string $key
	 * @return Model|bool
	 */
	public function activate($id, $key);

	/**
	 * Change password of the record
	 * @param Model $changeable
	 * @param Request $request
	 * @return Model
	 */
	public function change_password($changeable, $request);

	/**
	 * Set cookie to remember record on login
	 * @param Model $memorable
	 * @return Model
	 */
	public function remember($memorable);

	/**
	 * Logs the entity in
	 * @param Model $record
	 * @param string $session_name
	 * @return Model
	 */
	public function login($record, $session_name = '');

	/**
	 * Log the entity out
	 * @param string $session_name
	 * @param string $remember_cookie_name
	 * @return
	 */
	public function logout($session_name = '', $remember_cookie_name = '');
}