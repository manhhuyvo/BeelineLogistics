<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Enums\FulfillmentEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use Illuminate\Validation\Rule;

class FulfillmentController extends Controller
{
    /** Display the page for create new fulfillment */
    public function create(Request $request)
    {
        // Get all needed objects lists
        $staffsList = $this->formatStaffsList();
        $customersList = $this->formatCustomersList();
        $productsList = $this->formatProductsList();

        // Return the view
        return view('admin.fulfillment.create', [
            'staffsList' => $staffsList,
            'customersList' => $customersList,
            'productsList' => $productsList,
            'fulfillmentStatuses' => FulfillmentEnum::MAP_FULFILLMENT_STATUSES,
            'fulfillmentStatusColors' => FulfillmentEnum::MAP_STATUS_COLORS,
            'paymentStatuses' => FulfillmentEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => FulfillmentEnum::MAP_PAYMENT_COLORS,
            'countries' => FulfillmentEnum::MAP_COUNTRIES,
        ]);
    }

    /** Handle request for storing new fulfillment */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Validate and format the products list provided
        $productList = $this->validateAndFormatProductListRequest($request);
        if (!$productList) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_PRODUCTS_PROVIDED)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        // TODO: WE HAVE TO CONSIDER ON STOCK CONTROL AND INVOICE, BILLING STUFFS LATER ON

        // Calculate total product cost and labour cost
        $totalProductCost = $this->calculateTotalProductsCost($productList);
        // If some errors occured during the calculation process, just throw error messages to FE
        if (!$totalProductCost) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_PRODUCT_PRICING_RETRIEVE)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Get customer model
        $customerModel = Customer::find($data['customer_id']);
        // If for some reasons we cannot find the customer, just end it and throw errors back to FE
        if (!$customerModel) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_CUSTOMER_RETRIEVE)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        // If this customer doesn't have any pricing configs for fulfillment, just end it and throw errors back to FE
        if (empty($customerModel->price_configs) || empty(unserialize($customerModel->price_configs)['fulfillment_pricing'])) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_CUSTOMER_PRICING_RETRIEVE)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        // Get customer's default labour config
        $customerFulfillmentPricing = unserialize($customerModel->price_configs)['fulfillment_pricing'] ?? [];
        // We will always have something for the labour cost, so no need to validate
        $totalLabourCost = $this->calculateTotalLabourCost($customerFulfillmentPricing, $totalProductCost);

        // Set data for the product amount and labour amount
        $fulfillmentTotalAmounts = [
            'total_product_amount' => $totalProductCost['amount'] ?? 0,
            'product_unit' => $totalProductCost['unit'] ?? '',
            'total_labour_amount' => $totalLabourCost['amount'] ?? 0,
            'labour_unit' => $totalLabourCost['unit'] ?? '',
        ];

        // Set data for the product list configs
        $productList = [
            'product_configs' => serialize($productList),
        ];

        // Merge the fulfillment details and the product configs together
        $data = array_merge($data, $productList, $fulfillmentTotalAmounts);

        // Creating new record of fulfillment
        $newFulfillment = new Fulfillment($data);
        if (!$newFulfillment->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.fulfillment.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.fulfillment.create.form')->with(['response' => $responseData]);
    }

    /** Display the page for viewing fulfillment */
    public function show(Request $request, Fulfillment $fulfillment)
    {
        // Get staff model
        $staff = collect($fulfillment->staff)->toArray();

        // Get customer model
        $customer = collect($fulfillment->customer)->toArray();

        // Turn the fulfillment into an array
        $fulfillment = collect($fulfillment)->toArray();

        // Get product model and assign it to the fulfillment
        $fulfillment['product_configs'] = collect(unserialize($fulfillment['product_configs']))->map(function ($product) {
            // Find the product model, product group and turn it to array
            $productModel = Product::find($product['product_id']) ?? [];
            $productGroup = !empty($productModel) ? collect($productModel->productGroup)->toArray() : [];
            $product['model'] = array_merge(collect($productModel)->toArray(), ['product_group' => $productGroup]);

            // Return this product back to list
            return $product;
        })->toArray();

        // Return the view
        return view('admin.fulfillment.show', [
            'fulfillment' => $fulfillment,
            'staff' => $staff,
            'customer' => $customer,
        ]);
    }

    /** Display the page for edit fulfillment */
    public function edit(Request $request, Fulfillment $fulfillment)
    {
        // Get all needed objects lists
        $staffsList = $this->formatStaffsList();
        $customersList = $this->formatCustomersList();
        $productsList = $this->formatProductsList();

        // Turn the fulfillment into an array
        $fulfillment = collect($fulfillment)->toArray();

        // Get product model and assign it to the fulfillment
        $fulfillment['product_configs'] = collect(unserialize($fulfillment['product_configs']))->map(function ($product) {
            // Find the product model, product group and turn it to array
            $productModel = Product::find($product['product_id']) ?? [];
            $product['model'] = collect($productModel)->toArray();

            // Return this product back to list
            return $product;
        })->toArray();

        // Return the view
        return view('admin.fulfillment.edit', [
            'fulfillment' => $fulfillment,
            'staffsList' => $staffsList,
            'customersList' => $customersList,
            'productsList' => $productsList,
            'fulfillmentStatuses' => FulfillmentEnum::MAP_FULFILLMENT_STATUSES,
            'fulfillmentStatusColors' => FulfillmentEnum::MAP_STATUS_COLORS,
            'paymentStatuses' => FulfillmentEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => FulfillmentEnum::MAP_PAYMENT_COLORS,
            'countries' => FulfillmentEnum::MAP_COUNTRIES,
        ]);
    }

    public function update(Request $request, Fulfillment $fulfillment)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Validate and format the products list provided
        $productList = $this->validateAndFormatProductListRequest($request);
        if (!$productList) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_PRODUCTS_PROVIDED)->send();

            return redirect()->route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        // TODO: WE HAVE TO CONSIDER ON STOCK CONTROL AND INVOICE, BILLING STUFFS LATER ON

        // Calculate total product cost and labour cost
        $totalProductCost = $this->calculateTotalProductsCost($productList);
        // If some errors occured during the calculation process, just throw error messages to FE
        if (!$totalProductCost) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_PRODUCT_PRICING_RETRIEVE)->send();

            return redirect()->route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Get customer model
        $customerModel = $fulfillment->customer;
        // If this customer doesn't have any pricing configs for fulfillment, just end it and throw errors back to FE
        if (empty($customerModel->price_configs) || empty(unserialize($customerModel->price_configs)['fulfillment_pricing'])) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_CUSTOMER_PRICING_RETRIEVE)->send();

            return redirect()->route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        // Get customer's default labour config
        $customerFulfillmentPricing = unserialize($customerModel->price_configs)['fulfillment_pricing'] ?? [];
        // We will always have something for the labour cost, so no need to validate
        $totalLabourCost = $this->calculateTotalLabourCost($customerFulfillmentPricing, $totalProductCost);

        // Set data for the product amount and labour amount
        $fulfillmentTotalAmounts = [
            'total_product_amount' => $totalProductCost['amount'] ?? 0,
            'product_unit' => $totalProductCost['unit'] ?? '',
            'total_labour_amount' => $totalLabourCost['amount'] ?? 0,
            'labour_unit' => $totalLabourCost['unit'] ?? '',
        ];

        // Set data for the product list configs
        $productList = [
            'product_configs' => serialize($productList),
        ];

        // Merge the fulfillment details and the product configs together
        $data = array_merge($data, $productList, $fulfillmentTotalAmounts);

        // Update data for this record
        if (!$fulfillment->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.fulfillment.show', ['fulfillment' => $fulfillment->id])->with(['response' => $responseData]);
    }

    /** Calculate total cost of labour */
    private function calculateTotalLabourCost(array $labourConfigs, array $productCost)
    {
        // Initialize the total labour cost array
        $totalLabourCost = [
            'amount' => 0,
            'unit' => '',
        ];

        foreach ($labourConfigs as $type => $configs) {
            switch ($type) {
                // If this is 'Per Order', then we add the default amount into this total labour amount
                case 'fulfillment_per_order':
                    $totalLabourCost['amount'] = $totalLabourCost['amount'] + $configs['fulfillment_per_order_amount'];
                    $totalLabourCost['unit'] = $configs['fulfillment_per_order_unit'] ?? '';
                    break;
                // If this is 'Percentage', then we take (5 x $105)/100 and add this to the total labour cost for example
                case 'fulfillment_percentage':
                    $totalLabourCost['amount'] = $totalLabourCost['amount'] + ($configs['fulfillment_percentage_amount'] * $productCost['amount'])/100;
                    $totalLabourCost['unit'] = $productCost['unit'] ?? '';
                    break;
            }
        }

        // Eventually just return the total cost array
        return $totalLabourCost;
    }

    /** Calculate total cost of product */
    private function calculateTotalProductsCost(array $productList)
    {
        // Initialize the total product cost array
        $totalProductCost = [
            'amount' => 0,
            'unit' => '',
        ];

        foreach ($productList as $product) {
            // Retrieve product model
            $productModel = Product::find($product['product_id']);

            // If for some reasons we cannot find the product, just end it and throw errors back to FE
            if (!$productModel) {
                return false;
            }

            // Get product price configs
            $productPricing = unserialize($productModel->price_configs);

            // Add amount of each product into the sum amount
            if ($productPricing['type'] == 'unit') {
                $totalProductCost['amount'] = $totalProductCost['amount'] + $productPricing['price'] * $product['quantity'];
            }

            // Assign the currency (unit)
            $totalProductCost['unit'] = $productPricing['unit'];
        }

        // Eventually just return the total cost array
        return $totalProductCost;
    }
    
    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Customer Details
            "staff_id" => ["required", "integer"],
            "customer_id" => ["required", "integer"],
            "name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "address" => ["required"],
            "suburb" => ["required"],
            "state" => ["required"],
            "postcode" => ["required", "integer"],
            "country" => ["required", Rule::in(array_keys(CurrencyAndCountryEnum::MAP_COUNTRIES))],
            "fulfillment_status" => ["required", Rule::in(array_keys(FulfillmentEnum::MAP_FULFILLMENT_STATUSES))],
            "product_payment_status" => ["required", Rule::in(array_keys(FulfillmentEnum::MAP_PAYMENT_STATUSES))],
            "labour_payment_status" => ["required", Rule::in(array_keys(FulfillmentEnum::MAP_PAYMENT_STATUSES))],
            "shipping_type" => ["required", Rule::in(array_keys(FulfillmentEnum::MAP_SHIPPING))],
            "postage" => ["nullable", "numeric", "between:0, 9999.99"],
            "tracking_number" => ["nullable", "alpha_num"],
        ]);

        return $validator;
    }

    /** Validate and format product list request for store and update function */
    private function validateAndFormatProductListRequest(Request $request)
    {
        // Assign needed request keys to variables
        $selectedProducts = $request->all()['selected_products'] ?? [];
        $selectedQuantities = $request->all()['selected_quantities'] ?? [];

        // If user didn't add any product rows at all, then return false
        if (empty($selectedProducts) || empty($selectedQuantities)) {
            return false;
        }

        // Check and filter out any row that has null product or quantity
        $finalData = [];
        foreach ($selectedProducts as $index => $productId) {
            if (empty($productId) || empty($selectedQuantities[$index])) {
                continue;
            }

            // If there are some duplicated products, then we add total quantities of them together
            $duplicate = !empty($finalData) 
                        ? collect($finalData)->filter(function ($row) use ($productId) {
                            return $row['product_id'] == $productId;
                        })->first()
                        : [];
            // If we found a duplicate record, then we add the extra quantity into the current quantity
            if (!empty($duplicate)) {
                $finalData[$productId]['quantity'] += $selectedQuantities[$index];
                continue;
            }

            // Otherwise if it doesn't exist, then simply add this array to the list
            $finalData[$productId] = [
                'product_id' => $productId,
                'quantity' => $selectedQuantities[$index],
            ];
        }

        // If the saniztized products list is still empty, then return false
        if (empty($selectedProducts) || empty($selectedQuantities)) {
            return false;
        }

        // Otherwise if there are some valid products provided, return that product list
        return $finalData;
    }

    /** Format the data before saving to database */
    private function formatRequestData(Request $request)
    {
        $data = $request->all();

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }

        $data['postage_unit'] = CurrencyAndCountryEnum::MAP_CURRENCIES[$data['country']] ?? '';

        return collect($data)->only([
            'staff_id',
            'customer_id',
            'name',
            'phone',
            'address',
            'address2',
            'suburb',
            'state',
            'postcode',
            'country',
            'fulfillment_status',
            'product_payment_status',
            'labour_payment_status',
            'note',
            'shipping_type',
            'tracking_number',
            'postage',
            'postage_unit',
        ])->toArray();
    }

    /** Format the array for products list */
    private function formatProductsList()
    {
        // Load all products with their groups
        $allProducts = Product::with('productGroup')->get();
        // Get group name and filter the un-used keys
        $allProducts = collect($allProducts)->mapWithKeys(function(Product $product, int $index) {
            $product['group_name'] = $product->productGroup->name ?? '';
            $product = collect($product)->only(['id', 'group_id', 'group_name', 'name', 'stock', 'status'])->toArray();
            $formattedStatus = Product::MAP_STATUSES[$product['status']];

            return [$product['id'] => "{$product['name']} - Group: {$product['group_name']} - Stock: {$product['stock']} ({$formattedStatus})"];
        })->toArray();

        return $allProducts;
    }

    /** Format the array for customers list */
    private function formatCustomersList(string $listType = '')
    {
        $filterStatuses = (!empty($listType) && $listType  == "all") ? Customer::CUSTOMER_STATUSES : [
            Customer::STATUS_ACTIVE,
            Customer::STATUS_PENDING,
        ];

        $allCustomers = Customer::whereIn('status', $filterStatuses)
                    ->select('id', 'full_name', 'customer_id')
                    ->get();
        
        $data = [];
        foreach ($allCustomers as $customer) {
            $data[$customer['id']] = "{$customer['full_name']} ({$customer['customer_id']})";
        }

        return $data;
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
}
