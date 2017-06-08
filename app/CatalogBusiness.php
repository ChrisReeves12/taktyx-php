<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class CatalogBusiness extends Model implements ISQLStorable
{
    protected $fillable = [
    	'name', 'password', 'email', 'activation_digest', 'reset_digest', 'phone_number_id',
		'first_name', 'last_name', 'summary', 'status', 'address_id',
		'remember_digest', 'reset_digest_expire'
	];

    protected $hidden = [
    	'password', 'activation_digest', 'reset_digest', 'remember_digest'
	];

    // returns the address of the business
	public function address(){
		return $this->belongsTo(Address::class);
	}

	// returns the address of the phone
	public function phone_number(){
		return $this->belongsTo(PhoneNumber::class);
	}

	// returns all the services that belong to a business
	public function services(){
		return $this->hasMany(CatalogService::class);
	}

	// returns if the parent business is an enterprise
	public function is_enterprise(){
		return $this->enterprise == true;
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [
			'name' => 'required|max:80',
			'email' => 'email|required|max:100|unique:catalog_businesses',
			'password' => 'alpha_num|required|min:7|confirmed',
			'first_name' => 'max:80',
			'last_name' => 'max:80'
		];
	}
}
