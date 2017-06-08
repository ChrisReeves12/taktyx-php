<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $fillable = ['country_id', 'area_code', 'number'];

    // returns the country of a phone number
	public function country(){
		return $this->belongsTo('App\Country');
	}
}


