<?php
/**
 * The CountryRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Customer;
use App\Image;
use App\Services\MailerService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Validator;

/**
 * Class CustomerRepository
 * @package App\Services\Repositories
 */
class CustomerRepository extends BaseRepositoryImpl implements IRepository
{
	private $mailer;
	private $token_service;
	private $image_repository;

	/**
	 * CustomerRepository constructor.
	 * @param MailerService $mailer
	 * @param TokenService $token_service
	 * @param ImageRepository $image_repository
	 */
	public function __construct(MailerService $mailer, TokenService $token_service, ImageRepository $image_repository)
	{
		$this->mailer = $mailer;
		$this->token_service = $token_service;
		$this->image_repository = $image_repository;
		$this->setClass(Customer::class);
	}

	/**
	 * Save Customer
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Customer
	 */
	public function store($request, $foreign_key = null)
	{
		// validations
		$this->_validate_class();
		$this->_validate_record($request->all());

		// create new customer
		$customer = new Customer;
		$customer->username = $request['username'];
		$customer->password = bcrypt($request['password']);
		$customer->email = strtolower($request['email']);

		// set default image
		$image = new Image(['path' => $this->image_repository->generic('customer')]);
		$this->image_repository->save($image);
		$customer->image()->associate($image);

		// save the user
		$this->save($customer);

		return $customer;
	}

	/**
	 * @param Customer $customer
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Customer
	 */
	public function update($customer, $request, $foreign_key = null)
	{
		// validate username
		Validator::make($request->all(), ['username' => 'required|max:40|alpha_dash'])->validate();

		// validate email only if it is changed
		if($request['email'] != $customer->email)
		{
			Validator::make($request, ['email' => 'required|email|max:100|unique:customers'])->validate();
		}

		// validate password only if it is changed
		if(!empty($request['password']) || !empty($request['password_confirmation']))
		{
			Validator::make($request->all(), ['password' => 'required|min:7|confirmed|alpha_num'])->validate();
		}

		// update info
		$customer->username = $request->username;
		$customer->email = strtolower($request->email);

		// we only want to update password if it has been changed...
		if(!empty($request->password))
		{
			$customer->password = bcrypt($request->password);
		}

		$this->save($customer);

		// return customer in case it is needed
		return $customer;
	}
}