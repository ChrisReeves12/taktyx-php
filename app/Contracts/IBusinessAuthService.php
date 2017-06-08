<?php
/**
 * The IBusinessAuthService interface definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Contracts;

use App\CatalogBusiness;

/**
 * Interface IBusinessAuthService
 * @package App\Contracts
 */
interface IBusinessAuthService extends IAuthService
{
	/**
	 * Gets the currently logged in business
	 * @return CatalogBusiness
	 */
	public function business();

	/**
	 * Returns true if user is not logged in as a business
	 * @return bool
	 */
	public function non_business(): bool;
}