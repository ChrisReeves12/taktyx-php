<?php
/**
 * The CatalogSubCategoryRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\CatalogSubCategory;
use App\Contracts\IRepository;

/**
 * Class CatalogSubCategoryRepository
 * @package App\Services\Repositories
 */
class CatalogSubCategoryRepository extends BaseRepositoryImpl implements IRepository
{
	/**
	 * CatalogSubCategoryRepository constructor.
	 */
	public function __construct()
	{
		$this->setClass(CatalogSubCategory::class);
	}
}