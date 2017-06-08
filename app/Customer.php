<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model implements ISQLStorable
{

	protected $fillable = ['username', 'email', 'password',
	'remember_digest', 'reset_digest', 'reset_digest_expire',
		'status', 'activation_digest', 'address_id',
	'phone_number_id', 'image_id'];

	protected $hidden = ['password', 'remember_digest', 'reset_digest', 'activation_digest'];

	// returns avatar for customer
	public function image()
	{
		return $this->belongsTo(Image::class);
	}

	// returns address for customer
	public function address()
	{
		return $this->belongsTo(Address::class);
	}

	// returns phone number for customer
	public function phone_number()
	{
		return $this->belongsTo(PhoneNumber::class);
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return ['username' => 'required|max:40|alpha_dash',
				'email' => 'required|email|max:100|unique:customers',
				'password' => 'required|min:7|confirmed|alpha_num'];
	}

	// returns all takts sent to customer
    public function takts(){
	    return $this->hasMany(Takt::class);
    }
}
