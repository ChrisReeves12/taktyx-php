<?php
/**
 * The HomeController class definition.
 *
 * Main entry controller for the home page
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class HomeController
 * @package App\Http\Controllers\Frontend
 */
class HomeController extends Controller
{
	public function index()
	{
		return view('frontend.home.index');
	}
}