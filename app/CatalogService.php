<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class CatalogService extends Model implements ISQLStorable
{
    protected $fillable = [
    	'name', 'address_id', 'catalog_business_id', 'phone_number_id', 'catalog_sub_category_id',
		'image_id', 'email', 'first_name', 'last_name', 'summary', 'status', 'keywords', 'takt_enabled',
		'cache_longitude', 'cache_latitude'
	];

    // returns the subcategory of a service
	public function subcategory(){
		return $this->belongsTo(CatalogSubCategory::class, 'catalog_sub_category_id');
	}

	// returns the address of a service
	public function address(){
		return $this->belongsTo(Address::class);
	}

	// returns the business to which the service belongs
	public function business(){
		return $this->belongsTo(CatalogBusiness::class, 'catalog_business_id');
	}

	// returns the image of the service
	public function image(){
		return $this->belongsTo(Image::class);
	}

	// returns the phone number
	public function phone_number(){
		return $this->belongsTo(PhoneNumber::class);
	}

	// returns if the service belongs to a business with enterprise features
	public function is_enterprise(){
		return $this->business->enterprise == true;
	}

	// returns the employees for a service
	public function employees(){
		return $this->belongsToMany(Employee::class);
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [
			'name' => 'required|max:100',
			'email' => 'max:100|email',
			'first_name' => 'max:50',
			'last_name' => 'max:50',
			'address_line_1' => 'required',
			'postal_code' => 'required',
			'image' => 'image|max:150|mimes:jpeg,gif,png'
		];
	}

	// get the takts that were sent to this service
    public function takts(){
	    return $this->hasMany(Takt::class);
    }
}
