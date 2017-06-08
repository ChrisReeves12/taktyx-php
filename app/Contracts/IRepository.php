<?php
/**
 * Created by PhpStorm.
 * User: reeve
 * Date: 4/5/2017
 * Time: 10:31 PM
 */

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface IRepository
 * @package App\Contracts
 */
interface IRepository
{
	public function store($request, $foreign_key);

	public function update($updateable, $request, $foreign_key);

	public function save($saveable);

	public function find($id);

	public function find_by($column, $value);

}