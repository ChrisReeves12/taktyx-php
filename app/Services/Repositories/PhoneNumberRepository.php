<?php
/**
 * The PhoneNumberRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Country;
use App\PhoneNumber;
use Illuminate\Http\Request;

/**
 * Class PhoneNumberRepository
 * @package App\Services\Repositories\
 */
class PhoneNumberRepository extends BaseRepositoryImpl implements IRepository
{
	private $country_repository;

	/**
	 * PhoneNumberRepository constructor.
	 * @param CountryRepository $country_repository
	 */
	public function __construct(CountryRepository $country_repository)
	{
		$this->country_repository = $country_repository;
		$this->setClass(PhoneNumber::class);
	}

	/**
	 * Save phone number
	 * @param Request $request
	 * @param string $foreign_key
	 * @return PhoneNumber
	 */
	public function store($request, $foreign_key = null)
	{
		// create new number
		$phone_number = new PhoneNumber([
			'area_code' => $request->area_code,
			'number' => $request->number,
		]);

		// as a failsafe for development, give a default value if no country is present
		if(empty($request->country_id))
		{
			$country = new Country(['name' => "United States", 'dialing_code' => 1]);
			$this->country_repository->save($country);
			$phone_number->country()->associate($country);
		} else {
			// attach country normally
			$country = $this->country_repository->find($request->country_id);
			$phone_number->country()->associate($country);
		}

		// save the number
		$this->save($phone_number);

		// return phone number
		return $phone_number;
	}

	/**
	 * Update phone number
	 * @param PhoneNumber $phone_number
	 * @param Request $request
	 * @param string $foreign_key
	 * @return PhoneNumber
	 */
	public function update($phone_number, $request, $foreign_key = null)
	{
		// update number
		$phone_number->area_code = $request->area_code;
		$phone_number->number = $request->number;

		// re-attach country
		$country = $this->country_repository->find($request->country_id);
		$phone_number->country()->associate($country);

		// save
		$this->save($phone_number);

		return $phone_number;
	}
}