<?php
/**
 * The AuthController class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Http\Controllers\Frontend;

use App\CatalogBusiness;
use App\Contracts\IBusinessAuthService;
use App\Contracts\ICustomerAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Contracts\IRepository;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Services\Repositories\CatalogBusinessRepository;
use App\Services\Repositories\EmployeeRepository;
use App\Services\MailerService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend
 */
class AuthController extends Controller
{
	private $repository;
	private $mailer;
	private $customer_auth_service;
	private $business_auth_service;
	private $employee_auth_service;

	/**
	 * AuthController constructor.
	 * @param IRepository $repository
	 * @param IBusinessAuthService $business_auth_service
	 * @param ICustomerAuthService $customer_auth_service
	 * @param IEmployeeAuthService $employee_auth_service
	 * @param MailerService $mailer
	 */
	public function __construct(IRepository $repository, IBusinessAuthService $business_auth_service,
								ICustomerAuthService $customer_auth_service, IEmployeeAuthService $employee_auth_service, MailerService $mailer)
	{
		$this->repository = $repository;
		$this->mailer = $mailer;
		$this->customer_auth_service = $customer_auth_service;
		$this->business_auth_service = $business_auth_service;
		$this->employee_auth_service = $employee_auth_service;
	}

	/**
	 * Log out customer
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function customer_logout()
	{
		$this->customer_auth_service->logout();
		return redirect('/');
	}

	/**
	 * Log in customer
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function customer_login()
	{
		return view('frontend.auth.customer.login');
	}

	/**
	 * Verify customer log in
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function customer_login_verify(Request $request)
	{
		// if verified, log user in
		$customer = $this->customer_auth_service->verify($request);

		if($customer instanceof Customer)
		{
			// log in
			$this->customer_auth_service->login($customer);

			// remember customer if "remember me" was checked
			if($request->remember)
			{
				$this->customer_auth_service->remember($customer);
				$ret_val = redirect()->intended();
			}

			// redirect to where the user was intending to go
			$ret_val = redirect()->intended();
		}
		else
		{
			// handle login failure
			Session::flash('login_fail', __('Your username and/or password is incorrect.'));
			$ret_val = redirect('/login');
		}

		return $ret_val;
	}

	/**
	 * Activate customer
	 * @param int $id
	 * @param string $key
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function activate_customer($id, $key)
	{
		$this->customer_auth_service->activate($id, $key);
		return redirect('/');
	}

	/**
	 * Page for forgot password screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function customer_forgot_password()
	{
		return view('frontend/auth/customer/forgot_password');
	}

	/**
	 * Sends reset email
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function send_reset_email(Request $request)
	{
		// get the user's email and send email
		if($customer = $this->repository->find_by('email', $request->email))
		{
			$this->mailer->send_reset_email($customer, 'User Password Reset', $customer->username);
			Session::flash('info', 'An email has been sent with instructions on how to reset your password.');
			$ret_val = redirect('/');
		}
		else
		{
			Session::flash('error', 'No customer account could be located with the email address entered.');
			$ret_val = redirect('/');
		}

		return $ret_val;
	}

	/**
	 * Resets customer's password
	 * @param int $id
	 * @param string $key
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function customer_password_reset($id, $key)
	{
		// verify password reset
		if($customer = $this->customer_auth_service->verify_password_reset($id, $key))
		{
			// go to the form where the password can be reset
			$ret_val = view('frontend/auth/customer/reset_password', compact('customer', 'key'));
		}
		else
		{
			// if the link expired or is invalid, redirect
			Session::flash('error', 'The link to reset your password is incorrect or has expired.');
			$ret_val = redirect('/login');
		}

		return $ret_val;
	}

	/**
	 * Update customer's password
	 * @param int $id
	 * @param string $key
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function customer_password_update($id, $key, Request $request)
	{
		// validate
		$this->validate($request, ['password' => 'min:7|confirmed', 'email' => 'max:100|required|email']);

		// reset the password
		if($customer = $this->customer_auth_service->verify_password_reset($id, $key))
		{
			$this->customer_auth_service->change_password($customer, $request);
			Session::flash('success', 'Your password has successfully been reset.');
			$ret_val = redirect('/login');
		}
		else
		{
			Session::flash('error', 'The link to reset your password is incorrect or has expired.');
			$ret_val = redirect('/login');
		}

		return $ret_val;
	}

	/**
	 * Resend customer activation
	 * @param TokenService $tokens
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function resend_customer_activation(TokenService $tokens)
	{
		$customer = $this->customer_auth_service->customer();
		if(!($customer instanceof Customer))
		{
			Session::flash('error', __('Please sign in to continue.'));
			$ret_val = redirect('/');
		}

		elseif($customer->status == 'inactive')
		{
			// send activation email
			$this->mailer->send_activation_email($customer, $customer->name);

			// redirect user, letting them know an activation email has been sent
			Session::flash('activation_required', __('An email has been sent with instructions on how to complete your registration.'));

			$ret_val = redirect('/');
		}
		else
		{
			// this means user is already active
			Session::flash('already_activated', __('Your account is already active.'));
			$ret_val = redirect('/');
		}

		return $ret_val;
	}

	/**
	 * Activate business account
	 * @param int $id
	 * @param string $key
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_activation($id, $key)
	{
		if($this->business_auth_service->activate($id, $key))
		{
			Session::flash('business_activated', 'Your business has successfully been activated.');
		}
		else
		{
			Session::flash('business_activation_fail', 'There was a problem in activating your business.');
		}

		return redirect('/business');
	}

	/**
	 * Login business
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function business_login()
	{
		// only allow somebody who isn't already logged in as an employee
		if(!$this->employee_auth_service->employee())
		{
			$ret_val = view('frontend.auth.business.login');
		}
		else
		{
			Session::flash('error', 'Log out as an employee before logging in as a business.');
			$ret_val = redirect('/');
		}

		return $ret_val;
	}

	/**
	 * Verify business account
	 * @param Request $request
	 * @param CatalogBusinessRepository $business_repository
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_verify(Request $request, CatalogBusinessRepository $business_repository)
	{
		$business = $this->business_auth_service->verify($request);

		if($business instanceof CatalogBusiness)
		{
			$this->business_auth_service->login($business);

			// remember customer if "remember me" was checked
			if($request->remember)
			{
				$this->business_auth_service->remember($business);
			}

			$ret_val = redirect('/business');
		}
		else
		{
			Session::flash('business_login_fail', __('Your username and/or password is incorrect.'));
			$ret_val = redirect('/business/login');
		}

		return $ret_val;
	}

	/**
	 * Log business out
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_logout(){
		$this->business_auth_service->logout();
		return redirect('/business');
	}

	/**
	 * Forgot password view for business
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function business_forgot_password(){
		return view('frontend.auth.business.forgot_password');
	}

	/**
	 * Send password reset email for business
	 * @param Request $request
	 * @param CatalogBusinessRepository $business_repository
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_reset_password_email(Request $request, CatalogBusinessRepository $business_repository)
	{
		if($business = $business_repository->find_by('email', $request->email))
		{
			$this->mailer->send_reset_email($business, 'Business Password Reset', $business->name,
				'mail/business_password_reset');
			Session::flash('info', 'An email with instructions on how to reset your password has been sent.');
			$ret_val = redirect('/business/login');
		}
		else
		{
			Session::flash('error', 'No account with the email address entered could be located.');
			$ret_val = redirect('/business/login');
		}

		return $ret_val;
	}

	/**
	 * Business reset password
	 * @param int $id
	 * @param string $key
	 * @param CatalogBusinessRepository $business_repository
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function business_reset_password($id, $key, CatalogBusinessRepository $business_repository)
	{
		// verify password reset
		if($business = $this->business_auth_service->verify_password_reset($id, $key))
		{
			// go to the form where the password can be reset
			$ret_val = view('frontend/auth/business/reset_password', compact('business', 'key'));
		}
		else
		{
			// if the link expired or is invalid, redirect
			Session::flash('error', 'The link to reset your password is either incorrect or has expired.');
			$ret_val = redirect('/business/login');
		}

		return $ret_val;
	}

	/**
	 * Update business password
	 * @param int $id
	 * @param string $key
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_password_update($id, $key, Request $request)
	{
		// validate
		$this->validate($request, ['password' => 'min:7|confirmed', 'email' => 'required|max:100|email']);

		// update password
		if($business = $this->business_auth_service->verify_password_reset($id, $key)){
			$this->business_auth_service->change_password($business, $request);

			Session::flash('success', 'Your business password has been successfully reset.');
			$ret_val = redirect('/business/login');
		} else {
			Session::flash('error', 'The link to reset your password is either incorrect or has expired.');
			$ret_val = redirect('/business/login');
		}

		return $ret_val;
	}

	/**
	 * Employee login screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function employee_login()
	{
		// if business is already logged in, log out
		if(!$this->business_auth_service->business())
		{
			$ret_val = view('frontend.auth.employee.login');
		}
		else
		{
			Session::flash('error', 'Log out as a business before logging in as an employee.');
			$ret_val = redirect('/');
		}

		return $ret_val;
	}

	/**
	 * Log employee out
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function employee_logout()
	{
		// logs out employee
		$this->employee_auth_service->logout();
		return redirect('/business');
	}

	/**
	 * Log employee in
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function employee_login_go(Request $request)
	{
		// test to see if password is correct
		if($employee = $this->employee_auth_service->verify($request)){

			// if employee chose to be remembered, remember employee
			if($request->remember){
				$this->employee_auth_service->remember($employee);
			}

			$this->employee_auth_service->login($employee);
			$ret_val = redirect()->route('employee_select_service');
		} else {
			// handle login failure
			Session::flash('login_fail', __('Your username and/or password is incorrect.'));
			$ret_val = redirect('business/service/employees/login');
		}

		return $ret_val;
	}

	/**
	 * Employee forgot password screen
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function employee_forgot_password()
	{
		return view('frontend.auth.employee.forgot_password');
	}

	/**
	 * Sends password reset email for employee
	 * @param Request $request
	 * @param EmployeeRepository $employee_repository
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function employee_forgot_password_do(Request $request, EmployeeRepository $employee_repository)
	{
		if($employee = $employee_repository->find_by('email', $request->email))
		{
			$this->mailer->send_reset_email($employee, 'Employee Password Reset', $employee->first_name,
				'mail/employee_password_reset');
			Session::flash('info', 'An email has been sent with instructions on how to reset your password.');
			$ret_val = redirect('/business/service/employees/login');
		}
		else
		{
			Session::flash('error', 'No employee with the email address entered could be found.');
			$ret_val = redirect('/business/service/employees/login');
		}

		return $ret_val;
	}

	/**
	 * Employee password reset screen
	 * @param int $id
	 * @param string $key
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function employee_password_reset($id, $key)
	{
		// verify key
		if($employee = $this->employee_auth_service->verify_password_reset($id, $key))
		{
			$ret_val = view('frontend.auth.employee.password_reset', compact('employee', 'key'));
		}
		else
		{
			// otherwise, send error message
			Session::flash('error', 'The employee password reset link is either incorrect or expired.');
			$ret_val = redirect('/business/service/employees/login');
		}

		return $ret_val;
	}

	/**
	 * Action to perform employee password reset
	 * @param int $id
	 * @param string $key
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function employee_password_reset_do($id, $key, Request $request)
	{
		// validate
		$this->validate($request, [
			'email' => 'max:100|email|required',
			'password' => 'min:7|required|confirmed'
		]);

		// verify key
		if($employee = $this->employee_auth_service->verify_password_reset($id, $key))
		{
			// change the password
			$this->employee_auth_service->change_password($employee, $request);

			// send user back to login screen
			Session::flash('info', 'Your employee password was successfully reset');
			$ret_val = redirect('/business/service/employees/login');
		}
		else
		{
			// otherwise, send error message
			Session::flash('error', 'The employee password reset link is either incorrect or expired.');
			$ret_val = redirect('/business/service/employees/login');
		}

		return $ret_val;
	}

	/**
	 * Resend business activation email
	 * @param MailerService $mailer
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function business_resend_activation(MailerService $mailer)
	{
		$business = $this->business_auth_service->business();
		$mailer->send_activation_email($business, $business->name, 'mail/business_activation_email', 'subject');
		Session::flash('info', 'An email has been re-sent with instructions on how to activate your account.');
		return redirect('/');
	}
}
