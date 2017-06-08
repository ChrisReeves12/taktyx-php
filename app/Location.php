<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Location extends Model implements ISQLStorable
{
    protected $fillable = [
    	'latitude', 'longitude'
	];

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [];
	}
}
