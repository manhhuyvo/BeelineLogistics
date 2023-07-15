<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
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

        return view('admin.customer.show', [
            'customer' => $customer->toArray(),                      
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
            'staffsList' => $this->formatStaffsList(),
            'receiverZones' => Customer::MAP_ZONES,
            'customerStatusColors' => Customer::MAP_STATUSES_COLOR,
        ]);
    }

    /** Display the page for update customer details */
    public function edit(Request $request, Customer $customer)
    {
        // Get and validate customer data     
        $data = collect($customer)->toArray();
        $customer->default_sender = !empty($customer->default_sender) ? unserialize($data['default_sender']) : []; // turn default sender to array
        $customer->default_receiver = !empty($customer->default_receiver) ? unserialize($data['default_receiver']) : []; // turn default sender to array

        return view('admin.customer.edit', [
            'customer' => $customer->toArray(),                      
            'customerTypes' => Customer::MAP_TYPES,
            'customerStatuses' => Customer::MAP_STATUSES,
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
