<?php
/**
 * The CustomerReplyGateway class definition.
 *
 * Control access to service replies
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Gateways;
use App\Contracts\ICustomerAuthService;
use App\Takt;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;

/**
 * Class CustomerReplyGateway
 * @package App\Services\Gateways
 */
class CustomerReplyGateway
{
    private $cust_auth;

	/**
	 * CustomerReplyGateway constructor.
	 * @param ICustomerAuthService $cust_auth
	 */
    public function __construct(ICustomerAuthService $cust_auth)
	{
        $this->cust_auth = $cust_auth;
    }

	/**
	 * Restrict access to reply
	 * @param Takt $takt
	 * @return bool|RedirectResponse
	 */
    public function enact($takt)
	{
		$ret_val = true;

        // don't allow if customer isn't logged in
        if($this->cust_auth->guest())
        {
            $ret_val = redirect('/');
        }

        // if customer didn't write takt, he shouldn't be allowed to reply
        elseif($takt->author != $this->cust_auth->customer())
        {
            Session::flash('error', 'You do not have permission to reply to this Takt.');
            $ret_val = false;
        }

        // if service is closed, don't allow reply
        elseif($takt->recipient->status == 'closed')
        {
            Session::flash('error', 'This service is currently closed. Please try again later.');
            $ret_val = false;
        }

        // if service is busy, don't allow
        elseif($takt->recipient->status == 'closed')
        {
            Session::flash('error', 'This service is currently busy with other customers. Please try again in a moment.');
            $ret_val = false;
        }

        // if takt is closed, don't allow
        elseif($takt->status == 'closed')
        {
            Session::flash('error', 'This takt has been closed.');
            $ret_val = false;
        }

        // if takt is in waiting, don't allow because service has to reply first
        elseif($takt->status == 'waiting')
        {
            Session::flash('error', 'The service must reply before you can issue a reply.');
            $ret_val = false;
        }

        // otherwise, return true
        return $ret_val;
    }
}