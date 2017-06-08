<?php
/**
 * The SQLStorable interface definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Contracts;

/**
 * Interface SQLStorable
 * @package App\Contracts
 */
interface ISQLStorable
{
	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array;
}