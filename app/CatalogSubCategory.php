<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class CatalogSubCategory extends Model implements ISQLStorable
{
    protected $fillable = [
    	'name', 'catalog_category_id'
	];

    public function category(){
    	return $this->belongsTo(CatalogCategory::class, 'catalog_category_id');
	}

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [];
	}
}
