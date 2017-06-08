<?php
/**
 * The AddressRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Address;
use App\Contracts\IRepository;
use App\Country;
use Illuminate\Http\Request;

/**
 * Class AddressRepository
 * @package App\Services\Repositories
 */
class AddressRepository extends BaseRepositoryImpl implements IRepository
{
	private $country_repository;
	private $location_repository;

	/**
	 * AddressRepository constructor.
	 * @param CountryRepository $country_repository
	 * @param LocationRepository $location_repository
	 */
	public function __construct(CountryRepository $country_repository, LocationRepository $location_repository)
	{
		$this->country_repository = $country_repository;
		$this->location_repository = $location_repository;
		$this->setClass(Address::class);
	}

	/**
	 * Store address
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Address
	 */
	public function store($request, $foreign_key = null)
	{
		$ret_val = null;

		// verify that address fields are not empty
		$this->_validate_class();
		$this->_validate_record($request->all());

		// create a new address
		$address = new Address([
			'line_1' => $request->address_line_1,
			'line_2' => $request->address_line_2,
			'postal_code' => $request->postal_code
		]);

		// as a failsafe for development, give a default value if no country is present
		if(empty($request->country_id))
		{
			$country = new Country(['name' => "United States", 'dialing_code' => 1]);
			$this->country_repository->save($country);
			$address->country()->associate($country);
		}
		else
		{
			// attach country normally
			$country = $this->country_repository->find($request->country_id);
			$address->country()->associate($country);
		}

		// create location from address and save address
		if($location = $this->location_repository->store($request))
		{
			// associate location with address and save location
			$address->location()->associate($location);
			$this->save($address);

			// return address in case we need it
			$ret_val = $address;
		}

		return $ret_val;
	}

	/**
	 * Update address
	 * @param Address $address
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Address
	 */
	public function update($address, $request, $foreign_key = null)
	{
		$ret_val = null;

		// verify that address fields are not empty
		$this->_validate_class();
		$this->_validate_record($request->all());

		// update info and save address
		$address->line_1 = $request->address_line_1;
		$address->line_2 = $request->address_line_2;
		$address->postal_code = $request->postal_code;

		// re-associate country
		$country = $this->country_repository->find($request->country_id);
		$address->country()->associate($country);

		// update location from address
		if($location = $this->location_repository->update($address->location, $request))
		{
			// save address
			$this->save($address);
			$ret_val = $address;
		}

		// return the address in case it is needed
		return $ret_val;
	}
}