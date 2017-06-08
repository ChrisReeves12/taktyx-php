<?php
/**
 * Created by PhpStorm.
 * User: reeve
 * Date: 4/6/2017
 * Time: 12:52 AM
 */

namespace App\Services;


class TokenService
{

	// creates a random token array. The first index, 'key' is the token (a string of random characters). The second
	// is 'key_encoded', which is an encrypted version that can be saved as a digest in the database.
	public function create()
	{
		$key = str_random(16);
		$key_encoded = bcrypt($key);
		$token_array = ['key' => $key, 'key_encoded' => $key_encoded];
		return $token_array;
	}

}