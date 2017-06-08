<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Image extends Model implements ISQLStorable
{
    protected $fillable = [
    	'path'
	];

    // returns image with the correct path
	public function getPathAttribute($value)
	{
		$new_string = "/img/" . $value;
		return $new_string;
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return ['image' => 'required|image|max:150|mimes:jpeg,gif,png'];
	}
}
