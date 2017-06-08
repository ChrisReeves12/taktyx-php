<?php
/**
 * The TaktRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Services\CustomerAuthServiceImpl;
use App\Takt;
use Validator;

/**
 * Class TaktRepository
 * @package App\Services\Repositories
 */
class TaktRepository extends BaseRepositoryImpl implements IRepository
{
    private $cust_auth;
    private $service_repository;

	/**
	 * TaktRepository constructor.
	 * @param CustomerAuthServiceImpl $cust_auth
	 * @param CatalogServiceRepository $service_repository
	 */
    public function __construct(CustomerAuthServiceImpl $cust_auth, CatalogServiceRepository $service_repository)
	{
        $this->cust_auth = $cust_auth;
        $this->service_repository = $service_repository;
        $this->setClass(Takt::class);
    }

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param null $controller
	 * @param null $service_id
	 * @return Takt
	 */
    public function store($request, $controller = null, $service_id = null)
    {
        // validate
        $this->_validate_class();
        $this->_validate_record($request->all());

        // save takt
        $takt = new Takt([
            'content' => $request->getContent(),
        ]);

        // associate takt with customer
        $customer = $this->cust_auth->customer();
        $takt->author()->associate($customer);

        // associate takt with recipient
        $recipient = $this->service_repository->find($service_id);
        $takt->recipient()->associate($recipient);

        // save the takt
        $this->save($takt);

        return $takt;
    }
}