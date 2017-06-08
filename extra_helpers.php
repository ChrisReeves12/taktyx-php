<?php
/*
|--------------------------------------------------------------------------
| Extra Helpers
|--------------------------------------------------------------------------
|
| Extra helper functions for Taktyx.
|
*/

if (! function_exists('static_assets')) {
	/**
	 * Get the path to the static assets.
	 *
	 * @param  string  $file
	 * @return string
	 */
	function static_assets($file = '')
	{
		return config('general.frontend_static_path') . $file;
	}
}