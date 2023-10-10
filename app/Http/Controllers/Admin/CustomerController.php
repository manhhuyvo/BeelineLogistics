<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Customer\Meta as CustomerMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CustomerMetaEnum;
use App\Enums\GeneralEnum;
use App\Enums\CurrencyAndCountryEnum;
use App\Models\Product;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /** Display the page for list of all customers */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allCustomers = Customer::with('account');

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || $key == 'page' || $key == '_method') {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allStaffs = $allCustomers->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allCustomers = $allCustomers->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allCustomers = $allCustomers->appends(request()->except('page'));        
        $returnData = collect($allCustomers)->only('data')->toArray();
        $paginationData = collect($allCustomers)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.customer.list', [
            'customers' => $returnData,
            'pagination' => $paginationData,   
            'customerStatusColors' => Customer::MAP_STATUSES_COLOR,         
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
            'staffsList' => $this->formatStaffsList(),
            'receiverZones' => Customer::MAP_ZONES,
            'request' => $data,
        ]);
    }

    /** Display page for create new customer */
    public function create(Request $request)
    {
        return view('admin.customer.create', [            
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
            'staffsList' => $this->formatStaffsList(),
            'receiverZones' => Customer::MAP_ZONES,
        ]);
    }

    /** Handle request for create new customer */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.customer.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newCustomer = new Customer($data);
        if (!$newCustomer->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.customer.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newCustomer->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.customer.create.form')->with(['response' => $responseData]);
    }

    /** Display the page for create new customer */
    public function show(Request $request, Customer $customer)
    {        
        // Get and validate customer data     
        $data = collect($customer)->toArray();
        $customer->default_sender = !empty($customer->default_sender) ? unserialize($data['default_sender']) : []; // turn default sender to array
        $customer->default_receiver = !empty($customer->default_receiver) ? unserialize($data['default_receiver']) : []; // turn default sender to array
        $customer->price_configs = !empty($customer->price_configs) ? unserialize($data['price_configs']) : []; // turn price configs to array

        $viewData = [            
            'customer' => $customer->toArray(),                      
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
            'staffsList' => $this->formatStaffsList(),
            'receiverZones' => Customer::MAP_ZONES,
            'customerStatusColors' => Customer::MAP_STATUSES_COLOR,
        ];

        if (!empty($customer->price_configs)) {
            if (!empty($customer->price_configs['fulfillment_pricing'])) {
                $viewData['fulfillment_pricing'] = $customer->price_configs['fulfillment_pricing'];
            }
        }

        return view('admin.customer.show', $viewData);
    }

    /** Display the page for update customer details */
    public function edit(Request $request, Customer $customer)
    {
        $user = Auth::user();
        $staff = $user->staff;
        $countryMeta = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_COUNTRY);
        $serviceMeta = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_SERVICE);

        // Get and validate customer data     
        $data = collect($customer)->toArray();
        $customer->default_sender = !empty($customer->default_sender) ? unserialize($data['default_sender']) : []; // turn default sender to array
        $customer->default_receiver = !empty($customer->default_receiver) ? unserialize($data['default_receiver']) : []; // turn default sender to array

        return view('admin.customer.edit', [
            'user' => collect($user)->toArray(),
            'customer' => $customer->toArray(),                      
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'services' => GeneralEnum::MAP_SERVICES,
            'currentCountriesMeta' => $countryMeta ? $countryMeta->getValue() : [],
            'currentServicesMeta' => $serviceMeta ? $serviceMeta->getValue() : [],
            'staffsList' => $this->formatStaffsList(),
            'receiverZones' => Customer::MAP_ZONES,
            'customerStatusColors' => Customer::MAP_STATUSES_COLOR,
        ]);
    }

    /** Handle request update customer details */
    public function update(Request $request, Customer $customer)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request, $customer->customer_id);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.customer.edit.form', ['customer' => $customer->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        if (!$customer->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.customer.edit.form', ['customer' => $customer->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.customer.show', ['customer' => $customer->id])->with(['response' => $responseData]);
    }

    /** Handle request delete customer */
    public function destroy(Request $request, Customer $customer)
    {         
        // Perform deletion
        if (!$customer->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.customer.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.customer.list')->with(['response' => $responseData]);
    }

    /** Displage page for edit price configuration */
    public function editPriceConfigsPage(Request $request, Customer $customer)
    {
        $priceConfigs = unserialize($customer->price_configs);

        return view('admin.customer.price-config', [
            'customer' => $customer->toArray(),
            'priceConfigs' => $priceConfigs,
            'units' => Product::UNITS,
        ]);
    }

    /** Handle request for saving price configuration */
    public function updatePriceConfigs(Request $request, Customer $customer)
    {
        // Validate the request coming
        $validation = $this->validatePriceConfigsRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.customer.price-configs.edit.form', ['customer' => $customer->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatPriceConfigsRequest($request);

        // Update details
        if (!$customer->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.customer.show', ['customer' => $customer->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.customer.show', ['customer' => $customer->id])->with(['response' => $responseData]);
    }    

    public function countryConfig(Request $request, Customer $customer)
    {
        $data = $request->only('countries');
        
        // If the list data 
        if (empty($data) || !is_array($data) || empty($data['countries'])) {
            // Get meta country of this supplier
            $countryMeta = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_COUNTRY);
            if (!$countryMeta) {
                $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
                return redirect()->back()->with(['response' => $responseData]);
            }

            // If this meta exist, then we delete it
            if (!$countryMeta->delete()) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();
    
                return redirect()->back()->with(['response' => $responseData]);
            }

            $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
            return redirect()->back()->with(['response' => $responseData]);
        }

        // If the list was provided as an array and not empty, we validate
        $eligibleItems = collect($data['countries'])
                    ->filter(function($item) {
                        return in_array($item, array_keys(CurrencyAndCountryEnum::MAP_COUNTRIES));
                    })
                    ->toArray();

        //If the list is empty after validation, that means we should throw error back to FE
        if (empty($eligibleItems)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ITEMS_PROVIDED)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        // Implode the items as a string and create record
        $value = implode(',', $eligibleItems);
        $newMeta = $customer->createMeta(CustomerMetaEnum::META_AVAILABLE_COUNTRY, $value ?? '');
        if (!$newMeta) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
    
        return redirect()->back()->with(['response' => $responseData]);
    }

    public function serviceConfig(Request $request, Customer $customer)
    {
        $data = $request->only('services');
        
        // If the list data 
        if (empty($data) || !is_array($data) || empty($data['services'])) {
            // Get meta service of this supplier
            $serviceMeta = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_SERVICE);
            if (!$serviceMeta) {
                $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
                return redirect()->back()->with(['response' => $responseData]);
            }

            // If this meta exist, then we delete it
            if (!$serviceMeta->delete()) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();
    
                return redirect()->back()->with(['response' => $responseData]);
            }

            $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
            return redirect()->back()->with(['response' => $responseData]);
        }

        // If the list was provided as an array and not empty, we validate
        $eligibleItems = collect($data['services'])
                    ->filter(function($item) {
                        return in_array($item, array_keys(GeneralEnum::MAP_SERVICES));
                    })
                    ->toArray();

        //If the list is empty after validation, that means we should throw error back to FE
        if (empty($eligibleItems)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ITEMS_PROVIDED)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        // Implode the items as a string and create record
        $value = implode(',', $eligibleItems);
        $newMeta = $customer->createMeta(CustomerMetaEnum::META_AVAILABLE_SERVICE, $value ?? '');
        if (!$newMeta) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($customer->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
    
        return redirect()->back()->with(['response' => $responseData]);
    }

    /** Validate price configs form request */
    private function validatePriceConfigsRequest(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, []);

        $validator->sometimes(
            'fulfillment_per_order', 
            ["required", "string", Rule::in(['on'])],
            function ($input) {
                return $input->apply_pricing_fulfillment == 'on' && empty($input->fulfillment_percentage);
            }
        );

        $validator->sometimes(
            'fulfillment_percentage', 
            ["required", "string", Rule::in(['on'])],
            function ($input) {
                return $input->apply_pricing_fulfillment == 'on' && empty($input->fulfillment_per_order);
            }
        );

        $validator->sometimes(
            'fulfillment_per_order_amount', 
            ["required", "numeric"],
            function ($input) {
                return $input->fulfillment_per_order == 'on';
            }
        );

        $validator->sometimes(
            'fulfillment_per_order_unit', 
            ["required", Rule::in(Product::UNITS)],
            function ($input) {
                return $input->fulfillment_per_order == 'on';
            }
        );

        $validator->sometimes(
            'fulfillment_percentage_amount', 
            ["required", "numeric"],
            function ($input) {
                return $input->fulfillment_percentage == 'on';
            }
        );
        
        return $validator;
    }

    private function formatPriceConfigsRequest(Request $request)
    {
        $data = $request->all();

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }

        $returnData = [];
        // Structure data for fulfillment per order
        if (!empty($data['fulfillment_per_order'])) {
            $returnData['fulfillment_pricing']['fulfillment_per_order'] = [
                'fulfillment_per_order_amount' => $data['fulfillment_per_order_amount'],
                'fulfillment_per_order_unit' => $data['fulfillment_per_order_unit'],
            ];
        }

        // Structure data for fulfillment percentage
        if (!empty($data['fulfillment_percentage'])) {
            $returnData['fulfillment_pricing']['fulfillment_percentage'] = [
                'fulfillment_percentage_amount' => $data['fulfillment_percentage_amount'],
                'fulfillment_percentage_unit' => '%',
            ];
        }

        // return for price configs
        return ['price_configs' => serialize($returnData)];
    }

    /** Format the array for staffs list */
    private function formatStaffsList(string $listType = '')
    {
        $filterStatuses = (!empty($listType) && $listType  == "all") ? Staff::STAFF_STATUSES : [
            Staff::STATUS_CURRENT,
            StafF::STATUS_TEMPORARY_OFF,
        ];

        $allStaffs = Staff::whereIn('status', $filterStatuses)
                    ->select('id', 'full_name', 'position')
                    ->get();
        
        $data = [];
        foreach ($allStaffs as $staff) {
            $position = strtoupper(Staff::MAP_POSITIONS[$staff['position']]) ?? "Position Not Found";
            $data[$staff['id']] = "{$staff['full_name']} ({$position})";
        }

        return $data;
    }

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request, string $customerId = '')
    {
        $validator = Validator::make($request->all(), [
            // Customer Details
            "customer_id" => empty($customerId) 
                            ? ["required", "unique:App\Models\Customer,customer_id"] 
                            : ["required", Rule::unique('App\Models\Customer')->ignore($customerId, 'customer_id')],
            "full_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "address" => ["required"],
            "staff_id" => ["required", "integer"],
            "type" => ["required", "integer"],
            "status" => ["required", "integer"],
            // Default Sender
            "default_sender_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "default_sender_phone" => ["required", "regex:/^[0-9\s]+$/"],
            "default_sender_address" => ["required"],
            // Default Receiver
            "default_receiver_zone" => ["required", "integer", Rule::in(Customer::RECEIVER_ZONES)],
            "default_receiver_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "default_receiver_phone" => ["required", "regex:/^[0-9\s]+$/"],
            "default_receiver_address" => ["required"],
        ]);

        return $validator;
    }

    /** Format the data before saving to database */
    private function formatRequestData(Request $request)
    {
        $data = $request->all();

        $data['default_sender'] = serialize([
            "full_name" => $data['default_sender_name'] ?? '',
            "phone" => $data['default_sender_phone'] ?? '',
            "address" => $data['default_sender_address'] ?? '',
        ]);

        $data['default_receiver'] = serialize([
            "zone" => $data['default_receiver_zone'] ?? '',
            "full_name" => $data['default_receiver_name'] ?? '',
            "phone" => $data['default_receiver_phone'] ?? '',
            "address" => $data['default_receiver_address'] ?? '',
        ]);

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }

        return collect($data)->only([
            'customer_id',
            'full_name',
            'phone',
            'address',
            'company',
            'staff_id',
            'default_sender',
            'default_receiver',
            'type',
            'status',
            'note',
        ])->toArray();
    }
}
