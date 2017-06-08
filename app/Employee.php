<?php

namespace App;

use App\Contracts\ISQLStorable;
use App\Services\Repositories\AddressRepository;
use App\Services\Repositories\ImageRepository;
use App\Services\Repositories\PhoneNumberRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model implements ISQLStorable
{
	protected $fillable = [
		'email', 'first_name', 'last_name', 'password', 'remember_digest',
		'reset_digest', 'reset_digest_expire', 'status', 'address_id',
		'image_id', 'phone_number_id', 'catalog_service_id', 'admin'
	];

	protected $hidden = [
		'password', 'remember_digest', 'reset_digest'
	];

	// returns the services for an employee
	public function services(){
		return $this->belongsToMany(CatalogService::class)
			->withPivot('admin', 'phone_number_id', 'address_id', 'image_id', 'status');
	}

	// returns the address for an employee
	public function address($service){

		// create repository
		$address_repository = app(AddressRepository::class);

		// get the service
		$service = $this->services()->where('catalog_service_id', '=', $service->id)->first();

		// get the address
		$address_id = $service->pivot->address_id;
		$address = $address_repository->find($address_id);

		return $address;
	}

	// returns the phone number for an employee
	public function phone_number($service){

		// create repository
		$phone_repository = app(PhoneNumberRepository::class);

		// get the service
		$service = $this->services()->where('catalog_service_id', '=', $service->id)->first();

		// get the phone number
		$phone_id = $service->pivot->phone_number_id;
		$phone_number = $phone_repository->find($phone_id);

		return $phone_number;
	}

	public function image($service){

		// create repository
		$image_repository = new ImageRepository;

		// get the service
		$service = $this->services()->where('catalog_service_id', '=', $service->id)->first();

		// get the address
		$image_id = $service->pivot->image_id;
		$image = $image_repository->find($image_id);

		return $image;
	}

	public function status($service){
		$service = $this->services()->where('catalog_service_id', '=', $service->id)->first();
		return $service->pivot->status;
	}

	// will make employee an admin of a service
	public function make_admin($cat_service){
		// find the service
		DB::table('catalog_service_employee')
			->where([['catalog_service_id', $cat_service->id], ['employee_id', $this->id] ])
			->update(['admin' => 1]);
	}

	// will remove employee as an admin
	public function revoke_admin($cat_service){
		// find the service
		DB::table('catalog_service_employee')
			->where([['catalog_service_id', $cat_service->id], ['employee_id', $this->id] ])
			->update(['admin' => 0]);
	}

	// determines if user is an admin
	public function is_admin($cat_service){

		// locate the service in question
		$service = $this->services()->where('catalog_service_id', '=', $cat_service->id)->first();
		if($service->pivot->admin){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [
			'email' => 'required|email|max:100',
			'password' => 'required|min:7|alpha_num|confirmed',
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50'
		];
	}
}
