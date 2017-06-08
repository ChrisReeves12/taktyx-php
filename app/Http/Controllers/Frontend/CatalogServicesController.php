<?php

namespace App\Http\Controllers\Frontend;
use App\Contracts\IBusinessAuthService;
use App\Http\Controllers\Controller;
use App\PhoneNumber;
use App\Services\Repositories\CatalogServiceRepository;
use App\Services\Repositories\CatalogSubCategoryRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\EmployeeRepository;
use App\Services\Repositories\PhoneNumberRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class CustomersController
 * @package App\Http\Controllers\Frontend
 */

class CatalogServicesController extends Controller
{
	private $country_repository;
	private $sub_cat_repository;
	private $cat_service_repository;
    private $bus_auth;

    public function __construct(CountryRepository $country_repository,
								CatalogSubCategoryRepository $sub_cat_repository,
								CatalogServiceRepository $cat_service_repository,
                                IBusinessAuthService $bus_auth){
		$this->country_repository = $country_repository;
		$this->sub_cat_repository = $sub_cat_repository;
		$this->cat_service_repository = $cat_service_repository;
        $this->bus_auth = $bus_auth;
    }

    public function create_service(){
		$subcategories = $this->sub_cat_repository->all();
    	$countries = $this->country_repository->all('name');
    	return view('frontend.catalog_business.catalog_service.create', compact('countries', 'subcategories'));
	}

	public function store_service(Request $request){
    	if(!$this->cat_service_repository->store($request, $this)){
			// if it returns failure, it is because the address is not valid
			Session::flash('geolocator_error',
				'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
			return redirect()->back();
		}
    	return redirect('/business');
	}

	public function edit_service($id){
		$subcategories = $this->sub_cat_repository->all();
		$countries = $this->country_repository->all('name');
		$service = $this->cat_service_repository->find($id);
		return view('frontend.catalog_business.catalog_service.edit', compact('service', 'subcategories', 'countries'));
	}

	public function delete_service($id, EmployeeRepository $employee_repository){

		// get the service
		$service = $this->cat_service_repository->find($id);

		// get the business from the service
		$business = $service->business;

		// remove all the employees from the service if it is an enterprise
		if($business->enterprise){
			foreach($service->employees as $employee){
				$employee_repository->deleteFromService($employee, $service);
			}
		}

		// delete the service
		$this->cat_service_repository->delete($service);

		return redirect()->back();
	}

	public function update_service($id, Request $request){
		$service = $this->cat_service_repository->find($id);

		if(!$this->cat_service_repository->update($service, $request, $this)){
			// if it returns failure, it is because the address is not valid
			Session::flash('geolocator_error',
				'We were unable to locate the address you entered in our postal records. Please make sure the address is valid and try again.');
			return redirect()->back();
		}
		return redirect('/business');
	}

	public function edit_phone($id){

		// get the service and countries
		$service = $this->cat_service_repository->find($id);
		$countries = $this->country_repository->all('name');

		// get the phone number or create new one
		if(empty($service->phone_number)){
			$phone_number = new PhoneNumber;
			// assign default country
			$phone_number->country_id = $service->address->country_id;
		} else {
			$phone_number = $service->phone_number;
		}

		return view('frontend.catalog_business.catalog_service.edit_phone',
			compact('service', 'countries', 'phone_number'));
	}

	public function update_phone($id, Request $request, PhoneNumberRepository $phone_repository){
		// get the service
		$service = $this->cat_service_repository->find($id);

		// either store phone number or create new one
		if(empty($service->phone_number)){
			$phone_number = $phone_repository->store($request, $this);
			$service->phone_number()->associate($phone_number);
			$this->cat_service_repository->save($service);
		} else {
			$phone_repository->update($service->phone_number, $request, $this);
		}
		return redirect('/business');
	}

	public function update_status($id, Request $request){
		$service = $this->cat_service_repository->find($id);
		$service->status = $request->status;
		$this->cat_service_repository->save($service);
		return redirect()->back();
	}

	public function show_service($id){
		// get the service
		$service = $this->cat_service_repository->find($id);
		return view('frontend.catalog_business.catalog_service.show', compact('service'));
	}

	public function all_takts($service_id){

        // get the service and the takts sent
        $service = $this->cat_service_repository->find($service_id);

        // make sure business has permission to view the takts
        if($this->bus_auth->business() == $service->business) {

            $takts = $service->takts()->paginate(15);
            $name = $service->name;

            // get info needed to view selected takts
            $taktable_id = $service->id;
            $taktable_type = 'catalog_service';

            return view('frontend.takts.all_takts', compact('name', 'takts', 'taktable_type', 'taktable_id'));
        } else {
            // business is trying to access takts for a service that doesn't belong to him...
            return redirect('/');
        }
    }

}
