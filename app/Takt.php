<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Takt extends Model implements ISQLStorable
{
    protected $fillable = [
      'content', 'customer_id', 'status', 'catalog_service_id'
    ];

    // gets the author of a takt
    public function author(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    // gets the recipient of a takt
    public function recipient(){
        return $this->belongsTo('App\CatalogService', 'catalog_service_id');
    }

    // gets the replies for a takt
    public function replies(){
        return $this->hasMany(Reply::class);
    }

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [
			'content' => 'required'
		];
	}
}
