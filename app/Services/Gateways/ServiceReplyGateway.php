<?php
/**
 * The ServiceReplyGateway class definition.
 *
 * Control access to service replies
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Gateways;

use App\Contracts\IBusinessAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Takt;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;

/**
 * Class ServiceReplyGateway
 * @package App\Services\Gateways
 */
class ServiceReplyGateway
{
    private $bus_auth;
    private $emp_auth;

	/**
	 * ServiceReplyGateway constructor.
	 * @param IBusinessAuthService $bus_auth
	 * @param IEmployeeAuthService $emp_auth
	 */
    public function __construct(IBusinessAuthService $bus_auth, IEmployeeAuthService $emp_auth){

        $this->bus_auth = $bus_auth;
        $this->emp_auth = $emp_auth;
    }

	/**
	 * Handle permission of sending tack reply
	 * @param Takt $takt
	 * @return bool|RedirectResponse
	 */
    public function enact($takt)
	{
		$ret_val = true;

        // if neither business or employee logged in, don't allow
        if($this->bus_auth->non_business() && $this->emp_auth->non_employee())
        {
            $ret_val = redirect('/');
        }

        // if business isn't an enterprise, don't allow somebody not logged in as a business to reply
        elseif($takt->recipient->business->enterprise === false)
        {
            if($this->bus_auth->non_business())
            {
                $ret_val = redirect('/');
            }
        }

        // if business isn't an enterprise, don't allow business to reply to service he doesn't own
        elseif(!$this->bus_auth->non_business()){
            if(!$service = $this->bus_auth->business()->services()->where('id', $takt->id)->first()){
                Session::flash('error', 'You do not have permission to respond to this Takt.');
                $ret_val = false;
            }
        }

        // if business is an enterprise, employee cannot reply unless he is a member of that service
        elseif($takt->recipient->business->enterprise == true)
        {
            if (!$this->emp_auth->non_employee())
            {
                if (!$employee = $this->emp_auth->employee()->services()->where('id', $takt->recipient->id)->first())
                {
                    Session::flash('error', 'You do not have permission to respond to this Takt.');
                    $ret_val = false;
                }
            }
        }

        // otherwise, return true
        return $ret_val;
    }
}