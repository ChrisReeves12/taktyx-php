<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\IBusinessAuthService;
use App\Contracts\ICustomerAuthService;
use App\Services\Gateways\CustomerReplyGateway;
use App\Services\Gateways\ServiceReplyGateway;
use App\Services\Gateways\TaktViewGateway;
use App\Services\Repositories\CatalogServiceRepository;
use App\Services\Repositories\CustomerRepository;
use App\Services\Repositories\ReplyRepository;
use App\Services\Repositories\TaktRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class TaktsController extends Controller
{

    private $service_repository;
    private $cust_auth;
    private $takt_repository;
    private $reply_repository;
    private $customer_repository;
    private $bus_auth;
    private $customer_reply_gateway;
    private $service_reply_gateway;
    private $takt_view_gateway;

    public function __construct(CatalogServiceRepository $service_repository, ICustomerAuthService $cust_auth,
                                    TaktRepository $takt_repository, ReplyRepository $reply_repository,
                                    CustomerRepository $customer_repository, IBusinessAuthService $bus_auth,
                                        CustomerReplyGateway $customer_reply_gateway,
                                        ServiceReplyGateway $service_reply_gateway, TaktViewGateway $takt_view_gateway){

        $this->service_repository = $service_repository;
        $this->cust_auth = $cust_auth;
        $this->takt_repository = $takt_repository;
        $this->reply_repository = $reply_repository;
        $this->customer_repository = $customer_repository;
        $this->bus_auth = $bus_auth;
        $this->customer_reply_gateway = $customer_reply_gateway;
        $this->service_reply_gateway = $service_reply_gateway;
        $this->takt_view_gateway = $takt_view_gateway;
    }

    public function create($service_id){

        // get the service
        $service = $this->service_repository->find($service_id);

        return view('frontend.takts.create', compact('service'));
    }

    public function store($service_id, Request $request){
        if($takt = $this->takt_repository->store($request, $this, $service_id)){
            Session::flash('success', "Your Takt was sent successfully!");
            return redirect('/');
        } else {
            return redirect()->back();
        }
    }

    public function view_takt($taktable_type, $taktable_id, $takt_id){

        // get the takt and author
        $takt = $this->takt_repository->find($takt_id);
        $author = $takt->author;

        if($this->takt_view_gateway->enact($takt, $taktable_type)) {

            // this will view the takt as a customer
            if ($taktable_type == 'customer') {

                // set up replies
                $customer = $this->cust_auth->customer();
                $replyable_id = $customer->id;
                $replyable_type = 'customer';
            }

            // this will view the takt as a service
            elseif ($taktable_type == 'catalog_service') {

                // set up replies
                $service = $this->service_repository->find($taktable_id);
                $replyable_id = $service->id;
                $replyable_type = 'catalog_service';
            }
            return view('frontend.takts.show', compact('replyable_type', 'replyable_id', 'takt', 'author'));
        } else {
            // Takt didn't pass the gateway
            return redirect('/');
        }
    }

    public function store_reply($replyable_type, $replyable_id, $takt_id, Request $request)
	{
        // get the takt the reply will belong to
        $takt = $this->takt_repository->find($takt_id);

        // associate the takt depending upon the replyable type
        if($replyable_type == 'customer')
        {
            // get the customer
            $customer = $this->customer_repository->find($replyable_id);

			$result_or_redirect = $this->customer_reply_gateway->enact($takt);
            if($result_or_redirect === true)
            {
                // save reply
                $reply = $this->reply_repository->store($request, $this);

                // set variables and associations
                $reply->replyable_type = $replyable_type;
                $this->reply_repository->save($reply);
                $reply->author()->associate($customer);
                $reply->takt()->associate($takt);
                $this->reply_repository->save($reply);

                $ret_val = redirect("/takts/{$replyable_type}/{$customer->id}/view/$takt->id");
            }
            elseif($result_or_redirect instanceof RedirectResponse)
			{
				// not allowed to send reply and needs to redirect
				$ret_val = $result_or_redirect;
			}
			else
			{
                // returned false and wasn't allowed to send reply for some reason
                $ret_val = redirect()->back();
            }
        }
        else
		{
            // get the service
            $service = $this->service_repository->find($replyable_id);

			$result_or_redirect = $this->customer_reply_gateway->enact($takt);
            if($result_or_redirect === true)
            {
                // save reply
                $reply = $this->reply_repository->store($request, $this);

                // set variables
                $reply->replyable_type = $replyable_type;
                $this->reply_repository->save($reply);
                $reply->status = 'active';
                $reply->author()->associate($service);
                $reply->takt()->associate($takt);
                $this->reply_repository->save($reply);

                $ret_val = redirect("/takts/{$replyable_type}/{$service->id}/view/$takt->id");
            }
            elseif($result_or_redirect instanceof RedirectResponse)
			{
				$ret_val = $result_or_redirect;
			}
            else
			{
                // service wasn't allowed to send reply for some reason
                $ret_val = redirect()->back();
            }
        }

        return $ret_val;
    }
}
