<?php
/**
 * The TaktViewGateway class definition.
 *
 * Control access to view takts.
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Gateways;

use App\Contracts\IBusinessAuthService;
use App\Contracts\ICustomerAuthService;
use App\Contracts\IEmployeeAuthService;
use App\Takt;

/**
 * Class TaktViewGateway
 * @package App\Services\Gateways
 */
class TaktViewGateway
{
    private $cust_auth;
    private $bus_auth;
    private $emp_auth;

	/**
	 * TaktViewGateway constructor.
	 * @param ICustomerAuthService $cust_auth
	 * @param IBusinessAuthService $bus_auth
	 * @param IEmployeeAuthService $emp_auth
	 */
    public function __construct(ICustomerAuthService $cust_auth, IBusinessAuthService $bus_auth,
                                IEmployeeAuthService $emp_auth){

        $this->cust_auth = $cust_auth;
        $this->bus_auth = $bus_auth;
        $this->emp_auth = $emp_auth;
    }

	/**
	 * Checks if access is allowed to view takt
	 * @param Takt $takt
	 * @param string $taktable_type
	 * @return bool
	 */
    public function enact($takt, $taktable_type){

    	$ret_val = true;

        // if employee, customer or service isn't logged in, do not allow
        if($this->cust_auth->guest() && $this->emp_auth->non_employee() && $this->bus_auth->non_business()){
            $ret_val = false;
        }

        // if the service sent the message isn't part of an enterprise, only an author or recipient can see it
        elseif($takt->enterprise == false) {
            if ($this->cust_auth->guest() && $this->bus_auth->non_business()) {
				$ret_val = false;
            }
        }

        // if the user doesn't match the author or recipient or isn't an employee of that service, reject viewing
        elseif(($takt->author != $this->cust_auth->customer()) &&
            (!$this->emp_auth->non_employee() && !$emp_check = $this->emp_auth->employee()->services()->where('id', $takt->recipient->id)) &&
            ($takt->recipient->business != $this->bus_auth->business())){
				$ret_val = false;
        }

        // don't allow a service to view as a customer.
        elseif($taktable_type == 'customer'){
            if($this->cust_auth->customer() != $takt->author || $this->cust_auth->guest()){
                $ret_val = false;
            }
        }

        // don't allow a customer to view as a service.
        elseif($taktable_type == 'catalog_service'){
            if($this->bus_auth->business() != $takt->recipient->business || $this->bus_auth->non_business()){
                $ret_val = false;
            }
        }

        // otherwise return true
        return $ret_val;
    }
}