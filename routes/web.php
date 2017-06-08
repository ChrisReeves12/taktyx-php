<?php

/**
 * These are the web roots that deal with the public facing part of the web application.
 */


use App\Location;
use App\Services\Repositories\AddressRepository;
use App\Services\Repositories\CatalogBusinessRepository;
use App\Services\Repositories\CatalogServiceRepository;
use Illuminate\Http\File;

Route::group(['namespace' => 'Frontend', 'middlewareGroups' => 'web'], function() {

	// Homepage
	Route::get('/', ['as' => 'frontend_home_index', 'uses' => 'HomeController@index']);

	// Customer Login
	Route::get('/login', ['as' => 'frontend_cust_login', 'uses' => 'AuthController@customer_login'])->middleware('cust_already_in');
	Route::post('/login', ['as' => 'frontend_cust_login_go', 'uses' => 'AuthController@customer_login_verify'])->middleware('cust_already_in');

	// Customer Logout
	Route::get('/logout', ['as' => 'logout_user', 'uses' => 'AuthController@customer_logout'])->middleware('cust_auth');

	// Account activation
	Route::get('/account_activation/{id}/{key}', ['as' => 'account_activation', 'uses' => 'AuthController@activate_customer']);
	Route::get('/resend_activation', ['as' => 'resend_activation', 'uses' => 'AuthController@resend_customer_activation'])->middleware('cust_auth');

	// Customer Registration
	Route::get('/register', ['as' => 'frontend_auth_register', 'uses' => 'CustomersController@create_customer']);
	Route::post('/register', ['as' => 'frontend_auth_store', 'uses' => 'CustomersController@store_customer']);

	// Customer forgot password
	Route::get('/forgot_password', ['as' => 'frontend_auth_forgot_password', 'uses' => 'AuthController@customer_forgot_password']);
	Route::post('/forgot_password_email', ['as' => 'frontend_auth_forgot_password_email', 'uses' => 'AuthController@send_reset_email']);
	Route::get('password_reset/{id}/{key}', ['as' => 'frontend_auth_password_send', 'uses' => 'AuthController@customer_password_reset']);
	Route::post('password_reset/{id}/{key}', ['as' => 'password_reset_update', 'uses' => 'AuthController@customer_password_update']);

	// Customer Dashboard
	Route::get('/customer_dashboard', ['as' => 'customer_dashboard', 'uses' => 'CustomersController@index'])->middleware('cust_auth');
	// edit address
	Route::get('/customer_dashboard/edit/address', ['as' => 'edit_cust_address', 'uses' => 'CustomersController@edit_address'])->middleware('cust_active');
	Route::post('/customer_dashboard/edit/address', ['as' => 'update_cust_address', 'uses' => 'CustomersController@update_address'])->middleware('cust_active');
	// edit user
	Route::get('/customer_dashboard/edit', ['as' => 'edit_customer', 'uses' => 'CustomersController@edit_customer'])->middleware('cust_auth');
	Route::post('/customer_dashboard/edit', ['as' => 'update_customer', 'uses' => 'CustomersController@update_customer'])->middleware('cust_auth');
	// edit avatar
	Route::get('/customer_dashboard/image/edit', ['as' => 'edit_customer_image', 'uses' => 'CustomersController@edit_image'])->middleware('cust_active');
	Route::post('/customer_dashboard/image/update', ['as' => 'update_customer_image', 'uses' => 'CustomersController@update_image'])->middleware('cust_active');
	// edit phone number
	Route::get('/customer_dashboard/phone/edit', ['as' => 'edit_customer_phone', 'uses' => 'CustomersController@edit_phone'])->middleware('cust_active');
	Route::post('/customer_dashboard/phone/update', ['as' => 'update_customer_phone', 'uses' => 'CustomersController@update_phone'])->middleware('cust_active');

	// Business Registration
	Route::get('/business', ['as' => 'business_index', 'uses' => 'CatalogBusinessesController@index']);
	Route::get('/business/new', ['as' => 'create_business', 'uses' => 'CatalogBusinessesController@create']);
	Route::post('/business/store', ['as' => 'store_business', 'uses' => 'CatalogBusinessesController@store']);
	Route::get('/business/activate/{id}/{key}', ['as' => 'activate_business', 'uses' => 'AuthController@business_activation']);
	Route::get('/business/resend_activation', ['as' => 'business_resend_activation', 'uses' => 'AuthController@business_resend_activation']);

	// Business login
	Route::get('/business/login', ['as' => 'business_login', 'uses' => 'AuthController@business_login'])->middleware('business_already_in');
	Route::post('/business/login', ['as' => 'business_login_go', 'uses' => 'AuthController@business_verify'])->middleware('business_already_in');

	// Business logout
	Route::get('/business/logout', ['as' => 'business_logout', 'uses' => 'AuthController@business_logout']);

	// Business Password reset
	Route::get('/business/forgot_password', ['as' => 'business_forgot_password', 'uses' => 'AuthController@business_forgot_password']);
	Route::post('/business/forgot_password/email', ['as' => 'business_reset_password_email', 'uses' => 'AuthController@business_reset_password_email']);
	Route::get('/business/reset_password/{id}/{key}', ['as' => 'business_reset_password', 'uses' => 'AuthController@business_reset_password']);
	Route::post('/business/password_reset/{id}/{key}', ['as' => 'business_password_reset_update', 'uses' => 'AuthController@business_password_update']);

	// Business Dashboard
	Route::get('/business_dashboard', ['as' => 'business_dashboard', 'uses' => 'CatalogBusinessesController@business_dashboard'])->middleware('bus_auth');
	// update address
	Route::get('/business_dashboard/address/edit', ['as' => 'edit_business_address', 'uses' => 'CatalogBusinessesController@edit_address'])->middleware('bus_active');
	Route::post('/business_dashboard/address/update', ['as' => 'update_business_address', 'uses' => 'CatalogBusinessesController@update_address'])->middleware('bus_active');
	// update business
	Route::get('/business_dashboard/edit', ['as' => 'edit_business', 'uses' => 'CatalogBusinessesController@edit_business']);
	Route::post('/business_dashboard/update', ['as' => 'update_business', 'uses' => 'CatalogBusinessesController@update_business']);
	// update phone number
	Route::get('/business_dashboard/phone/edit', ['as' => 'edit_business_phone', 'uses' => 'CatalogBusinessesController@edit_phone'])->middleware('bus_active');
	Route::post('/business_dashboard/phone/update', ['as' => 'update_business_phone', 'uses' => 'CatalogBusinessesController@update_phone'])->middleware('bus_active');

	// Services
	Route::get('/business/service/new', ['as' => 'create_service', 'uses' => 'CatalogServicesController@create_service'])->middleware('bus_active');
	Route::post('/business/service', ['as' => 'store_service', 'uses' => 'CatalogServicesController@store_service'])->middleware('bus_active');
	Route::get('/business/service/{id}/e	dit', ['as' => 'edit_service', 'uses' => 'CatalogServicesController@edit_service'])->middleware('bus_active');
	Route::post('/business/service/{id}', ['as' => 'update_service', 'uses' => 'CatalogServicesController@update_service'])->middleware('bus_active');
	Route::post('/business/service/{id}/delete', ['as' => 'delete_service', 'uses' => 'CatalogServicesController@delete_service'])->middleware('bus_active');
	Route::post('/business/service/{id}/update_status', ['as' => 'update_service_status', 'uses' => 'CatalogServicesController@update_status'])->middleware('bus_active');
	// add or edit phone
	Route::get('/business/service/phone/{id}/edit', ['as' => 'edit_service_phone', 'uses' => 'CatalogServicesController@edit_phone'])->middleware('bus_active');
	Route::post('/business/service/phone/{id}', ['as' => 'update_service_phone', 'uses' => 'CatalogServicesController@update_phone'])->middleware('bus_active');
	// show service
	Route::get('/business/service/{id}', ['as' => 'show_service_profile', 'uses' => 'CatalogServicesController@show_service']);

	// manage employees
	Route::get('/business/service/{service_id}/employees', ['as' => 'employees', 'uses' => 'EmployeesController@index'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/new', ['as' => 'employee_create', 'uses' => 'EmployeesController@create'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}/edit', ['as' => 'employee_edit', 'uses' => 'EmployeesController@edit'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees', ['as' => 'employee_store', 'uses' => 'EmployeesController@store'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}', ['as' => 'employee_show', 'uses' => 'EmployeesController@show'])->middleware('enterprise');
	Route::delete('/business/service/{service_id}/employees/{id}/delete', ['as' => 'employee_delete', 'uses' => 'EmployeesController@delete'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}/edit_address', ['as' => 'employee_edit_address', 'uses' => 'EmployeesController@edit_address'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees/{id}/edit_address', ['as' => 'employee_update_address', 'uses' => 'EmployeesController@update_address'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}/edit_phone', ['as' => 'employee_edit_phone', 'uses' => 'EmployeesController@edit_phone'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees/{id}/update_phone', ['as' => 'employee_update_phone', 'uses' => 'EmployeesController@update_phone'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}/edit_image', ['as' => 'employee_edit_image', 'uses' => 'EmployeesController@edit_image'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees/{id}/update_image', ['as' => 'employee_update_image', 'uses' => 'EmployeesController@update_image'])->middleware('enterprise');
	Route::get('/business/service/{service_id}/employees/{id}/existing_employee', ['as' => 'existing_employee_mailer_form',
					'uses' => 'EmployeesController@existing_employee_mailer_form'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees/{id}/ex_mail_send', ['as' => 'employee_mailer_send',
																				   'uses' => 'EmployeesController@employee_mailer_send'])->middleware('enterprise');
	Route::post('/business/service/{service_id}/employees/{id}/update_admin', ['as' => 'change_admin', 'uses' => 'EmployeesController@change_admin'])->middleware('enterprise');

	// employee user
	Route::get('/business/service/employees/login', ['as' => 'employee_login', 'uses' => 'AuthController@employee_login'])->middleware('employee_already_in');
	Route::post('/business/service/employees/login', ['as' => 'employee_login_go', 'uses' => 'AuthController@employee_login_go'])->middleware('employee_already_in');
	Route::get('/business/service/{service_id}/employees/{id}/edit_account', ['as' => 'employee_edit_account', 'uses' => 'EmployeesController@edit_account']);
	Route::patch('/business/service/{service_id}/employees/{id}', ['as' => 'employee_update_account', 'uses' => 'EmployeesController@update']);
	Route::get('/business/service/employees/logout', ['as' => 'employee_logout', 'uses' => 'AuthController@employee_logout']);
	Route::get('/business/service/{service_id}/employees/{id}/permit', ['as' => 'employee_permit_mailer', 'uses' => 'EmployeesController@employee_permit']);
	Route::post('/business/service/{service_id}/employees/{id}/permit', ['as' => 'employee_permit_verify', 'uses' => 'EmployeesController@employee_permit_verify']);
	Route::get('/business/service/employees/select_service', ['as' => 'employee_select_service', 'uses' => 'EmployeesController@select_service']);
	Route::post('/business/service/employees/select_service', ['as' => 'employee_select_service_do', 'uses' => 'EmployeesController@select_service_do']);
	Route::get('/business/service/{service_id}/employees/{id}/dashboard', ['as' => 'employee_dashboard', 'uses' => 'EmployeesController@employee_dashboard']);
	Route::get('/business/service/employees/forgot_password', ['as' => 'employee_forgot_password', 'uses' => 'AuthController@employee_forgot_password']);
	Route::post('/business/service/employees/forgot_password', ['as' => 'employee_forgot_password_do', 'uses' => 'AuthController@employee_forgot_password_do']);
	Route::get('/business/service/employees/{id}/{key}/password_reset', ['as' => 'employee_password_reset', 'uses' => 'AuthController@employee_password_reset']);
	Route::post('/business/service/employees/{id}/{key}/password_reset', ['as' => 'employee_password_reset_do', 'uses' => 'AuthController@employee_password_reset_do']);
	Route::post('/business/service/{service_id}/employees/manage', ['as' => 'employee_manage_employees', 'uses' => 'EmployeesController@admin_manage_employees']);

	// Takts
    Route::get('/business/service/{service_id}/takt/new', ['as' => 'new_takt', 'uses' => 'TaktsController@create'])->middleware('cust_active');
    Route::post('/business/service/{service_id}/takt/{takt_id}', ['as' => 'store_takt', 'uses' => 'TaktsController@store'])->middleware('cust_active');
    // customer see takts
    Route::get('/customer/takts', ['as' => 'customer_takts', 'uses' => 'CustomersController@all_takts'])->middleware('cust_active');
    // service see takts
    Route::get('/catalog_service/{service_id}/takts', ['as' => 'service_takts', 'uses' => 'CatalogServicesController@all_takts'])->middleware('bus_active');
    // view/reply to takt
    Route::get('/takts/{taktable_type}/{taktable_id}/view/{takt_id}', ['as' => 'view_takt', 'uses' => 'TaktsController@view_takt']);
    Route::post('/reply/{replyable_type}/{replyable_id}/{takt_id}', ['as' => 'store_reply', 'uses' => 'TaktsController@store_reply']);


	Route::get('/geolocate_test', function($id, AddressRepository $repository) {

		// get the service
		$address = $repository->find($id);
		$line_1 = '3250 Sweetwater Rd.';
		$line_2 = '';
		$postal_code = 'Lawrenceville, GA 30044';

		//get the coordinates
		$url_raw = "https://maps.googleapis.com/maps/api/geocode/json?address={$line_1}, {$postal_code}&key=AIzaSyA-ZpyK3mtJuhML4IRtelZde-0f3jF8I6U";
		$url = str_replace(" ", "+", $url_raw);
		$content = json_decode(file_get_contents($url));

		$longitude = $content->results[0]->geometry->location->lng;
		$latitude = $content->results[0]->geometry->location->lat;

		return view('frontend.testing', compact('longitude', 'latitude', 'address', 'line_2', 'line_1', 'postal_code'));
	});
});



