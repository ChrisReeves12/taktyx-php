<?php
/**
 * The CatalogBusinessRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\CatalogService;
use App\Contracts\IBusinessAuthService;
use App\Contracts\IRepository;
use App\Image;
use Illuminate\Http\Request;

/**
 * Class CatalogServiceRepository
 * @package App\Services\Repositories
 */
class CatalogServiceRepository extends BaseRepositoryImpl implements IRepository
{
	private $country_repository;
	private $address_repository;
	private $sub_cat_repository;
	private $image_repository;
	private $business_auth_service;

	/**
	 * CatalogServiceRepository constructor.
	 * @param CountryRepository $country_repository
	 * @param AddressRepository $address_repository
	 * @param CatalogSubCategoryRepository $sub_cat_repository
	 * @param ImageRepository $image_repository
	 * @param IBusinessAuthService $business_auth_service
	 */
	public function __construct(CountryRepository $country_repository,
								AddressRepository $address_repository,
								CatalogSubCategoryRepository $sub_cat_repository,
								ImageRepository $image_repository, IBusinessAuthService $business_auth_service)
	{
		$this->country_repository = $country_repository;
		$this->address_repository = $address_repository;
		$this->sub_cat_repository = $sub_cat_repository;
		$this->image_repository = $image_repository;
		$this->business_auth_service = $business_auth_service;
		$this->setClass(CatalogService::class);
	}

	/**
	 * Store service
	 * @param Request $request
	 * @param string $foreign_key
	 * @return CatalogService
	 */
	public function store($request, $foreign_key = null)
	{
		$ret_val = null;

		// controller validations
		$this->_validate_class();
		$this->_validate_record($request->all());

		// get the business to which this service will belong
		$business = $this->business_auth_service->business();

		// create the service
		$catalog_service = new CatalogService([
			'name' => $request->name,
			'email' => $request->email,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'summary' => $request->summary,
			'keywords' => $request->keywords,
		]);

		// create the address but only if it is valid
		if($address = $this->address_repository->store($request))
		{
			// update the cache
			$catalog_service->cache_longitude = $address->location->longitude;
			$catalog_service->cache_latitude = $address->location->latitude;

			// handle uploading image
			if(!empty($request->file('image')))
			{
				$image = $this->image_repository->store($request);
			}
			else
			{
				$image = new Image(['path' => $this->image_repository->generic('service')]);
				$this->image_repository->save($image);
			}

			// get the subcategory
			$subcategory = $this->sub_cat_repository->find($request->subcategory);

			// save the actual service
			$catalog_service->business()->associate($business);
			$catalog_service->address()->associate($address);
			$catalog_service->image()->associate($image);
			$catalog_service->subcategory()->associate($subcategory);
			$this->save($catalog_service);
			$ret_val = $catalog_service;
		}

		return $ret_val;
	}


	/**
	 * Update the service
	 * @param CatalogService $catalog_service
	 * @param Request $request
	 * @param string $foreign_key
	 * @return CatalogService
	 */
	public function update($catalog_service, $request, $foreign_key = null)
	{
		$ret_val = null;

		// controller validations
		$this->_validate_class();
		$this->_validate_record($request->all());

		// update info
		$catalog_service->name = $request->name;
		$catalog_service->email = $request->email;
		$catalog_service->first_name = $request->first_name;
		$catalog_service->last_name = $request->last_name;
		$catalog_service->summary = $request->summary;
		$catalog_service->keywords = $request->keywords;

		// update if takts are enabled
		if($request->takt_enabled)
		{
			$catalog_service->takt_enabled = true;
		}
		else
		{
			$catalog_service->takt_enabled = false;
		}

		// update the address but only if it is valid
		$address = $catalog_service->address;
		if($this->address_repository->update($address, $request))
		{
			// update the cache
			$catalog_service->cache_longitude = $address->location->longitude;
			$catalog_service->cache_latitude = $address->location->latitude;

			// handle uploading image
			if(!empty($request->file('image')))
			{
				$image = $catalog_service->image;
				$this->image_repository->update($image, $request);
			}

			// get the subcategory
			$subcategory = $this->sub_cat_repository->find($request->subcategory);

			// save the actual service
			$catalog_service->subcategory()->associate($subcategory);
			$this->save($catalog_service);
			$ret_val = $catalog_service;
		}

		return $ret_val;
	}
}