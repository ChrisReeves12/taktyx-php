<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Address extends Model implements ISQLStorable
{
    protected $fillable = [
    	'country_id', 'line_1', 'line_2', 'postal_code'
	];

    // returns the country of an address
    public function country(){
    	return $this->belongsTo(Country::class);
	}

	// returns the location of an address
	public function location(){
    	return $this->belongsTo(Location::class);
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return ['address_line_1' => 'required', 'postal_code' => 'required'];
	}
}
