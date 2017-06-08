<?php

namespace App;

use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model implements ISQLStorable
{
    protected $fillable = [
       'takt_id', 'content', 'replyable_type', 'replyable_id', 'status'
    ];

    // returns the takt the reply belongs to
    public function takt(){
        return $this->belongsTo(Takt::class);
    }

    // returns the author of the reply
    public function author(){
        if($this->replyable_type == 'customer'){
            $author = $this->belongsTo(Customer::class, 'replyable_id');
        } elseif($this->replyable_type == 'catalog_service'){
            $author = $this->belongsTo(CatalogService::class, 'replyable_id');
        }

        // return author
        return $author;
    }

	/**
	 * Get validation rules to be used
	 * @return array
	 */
	public function getValidationRules(): array
	{
		return [
			'reply_content' => 'required'
		];
	}
}
