<?php
/**
 * The CatalogBusinessRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\CatalogBusiness;
use App\Contracts\IRepository;
use Illuminate\Http\Request;
use Validator;

/**
 * Class CatalogBusinessRepository
 * @package App\Services\Repositories
 */
class CatalogBusinessRepository extends BaseRepositoryImpl implements IRepository
{
	/**
	 * CatalogBusinessRepository constructor.
	 */
	public function __construct()
	{
		$this->setClass(CatalogBusiness::class);
	}

	/**
	 * Store business
	 * @param Request $request
	 * @param string $foreign_key
	 * @return CatalogBusiness
	 */
	public function store($request, $foreign_key = null)
	{
		// form validations
		$this->_validate_class();
		$this->_validate_record($request->all());

		// create new business
		$business = new CatalogBusiness([
			'name' => $request->name,
			'email' => $request->email,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'summary' => $request->summary
		]);

		// save password and save business
		$business->password = bcrypt($request->password);
		$this->save($business);

		return $business;
	}

	/**
	 * Update business
	 * @param CatalogBusiness $business
	 * @param Request $request
	 * @param string $foreign_key
	 * @return CatalogBusiness
	 */
	public function update($business, $request, $foreign_key = null)
	{
		// validate username, first and last name
		Validator::make($request->all(), ['name' => 'required|max:80',
										  'first_name' => 'required|max:80',
										  'last_name' => 'required|max:80'])->validate();

		// validate email only if it is changed
		if($request['email'] != $business->email){
			Validator::make($request->all(), ['email' => 'required|email|max:100|unique:catalog_businesses'])->validate();
		}

		// validate password only if it is changed
		if(!empty($request['password']) || !empty($request['password_confirmation'])){
			Validator::make($request->all(), ['password' => 'min:7|confirmed|alpha_num'])->validate();
		}

		// update info
		$business->name = $request->name;
		$business->first_name = $request->first_name;
		$business->last_name = $request->last_name;
		$business->email = strtolower($request->email);
		$business->summary = $request->summary;

		// we only want to update password if it has been changed...
		if(!empty($request->password))
		{
			$business->password = bcrypt($request->password);
		}

		$this->save($business);

		// return customer in case it is needed
		return $business;
	}
}