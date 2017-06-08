<?php
/**
 * The CountryRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Country;
use Illuminate\Http\Request;

/**
 * Class CountryRepository
 * @package App\Services\Repositories
 */
class CountryRepository extends BaseRepositoryImpl implements IRepository
{
	/**
	 * CountryRepository constructor.
	 */
	public function __construct()
	{
		$this->setClass(Country::class);
	}

	/**
	 * Save country
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Country
	 */
	public function store($request, $foreign_key = null)
	{
		// validate fields
		$this->_validate_class();
		$this->_validate_record($request->all());

		// create new country
		$country = new Country([
			'name' => $request->name,
			'dialing_code' => $request->dialing_code
		]);

		// save country
		$this->save($country);
		return $country;
	}

	/**
	 * Update country
	 * @param Country $country
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Country
	 */
	public function update($country, $request, $foreign_key = null)
	{
		// validate fields
		$this->_validate_class();
		$this->_validate_record($request->all());

		// update data
		$country->name = $request->name;
		$country->dialing_code = $request->dialing_code;
		$this->save($country);

		// return country in case it's needed
		return $country;
	}
}