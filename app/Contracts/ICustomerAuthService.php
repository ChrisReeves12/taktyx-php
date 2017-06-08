<?php
/**
 * The ICustomerAuthService interface definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Contracts;

use App\Customer;

/**
 * Interface ICustomerAuthService
 * @package App\Contracts
 */
interface ICustomerAuthService extends IAuthService
{
	/**
	 * Gets the logged in customer
	 * @return Customer
	 */
	public function customer();

	/**
	 * Returns true if user is not logged in
	 * @return bool
	 */
	public function guest();

}