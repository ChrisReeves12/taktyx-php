<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements ISQLStorable
{
    protected $fillable = [
    	'name', 'dialing_code'
	];

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return ['name' => 'required|unique:countries', 'dialing_code' => 'required'];
	}
}
