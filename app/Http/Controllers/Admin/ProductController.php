<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Product\Group as ProductGroup;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /** Display the page for list of all products */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allProducts = Product::with(['productGroup', 'customer']);

        // Filter out if this is sales viewing their products
        $user = Auth::user();
        if (!$user->staff->isAdmin()) {
            $allProducts->whereHas('customer', function ($query) use ($user) {
                $query->where('staff_id', $user->staff->id);
            });
        }

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || in_array($key, ['page', '_method'])) {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allProducts = $allProducts->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allProducts = $allProducts->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allProducts = $allProducts->appends(request()->except('page'));  
        $returnData = collect($allProducts)->only('data')->toArray();
        $returnData['data'] = collect($returnData['data'])->map(function($product) {
            // Unserialize the price configs
            $product['price_configs'] = unserialize($product['price_configs']);

            return $product;
        })->toArray();
        $paginationData = collect($allProducts)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }
        
        // Get all the current product groups
        $allProductGroups = $this->getAllProductGroups();

        // Format the product groups so we can map them in view
        $allProductGroups = collect($allProductGroups)->mapWithKeys(function($group, int $index) {
            return [$group['id'] => $group['name']];
        });

        // Format the customers list for searchable dropdowns
        $customersList = $this->formatCustomersList();

        return view('admin.product.list', [
            'products' => $returnData,
            'pagination' => $paginationData,   
            'productGroups' => $allProductGroups,
            'productStatuses' => Product::MAP_STATUSES,
            'customersList' => $customersList,
            'units' => Product::UNITS,
            'productStatusColors' => Product::MAP_STATUSES_COLOR,
            'request' => $data,
        ]);
    }

    /** Display the page for create new product */
    public function create(Request $request)
    {
        // Get all the current product groups
        $allProductGroups = $this->getAllProductGroups();

        // Format the customers list for searchable dropdowns
        $customersList = $this->formatCustomersList();

        // Format the product groups so we can map them in view
        $allProductGroups = collect($allProductGroups)->mapWithKeys(function($group, int $index) {
            return [$group['id'] => $group['name']];
        });

        return view('admin.product.create', [
            'productGroups' => $allProductGroups,
            'productStatuses' => Product::MAP_STATUSES,
            'customersList' => $customersList,
            'units' => Product::UNITS,
        ]);
    }

    /** Display the page for view product details */
    public function show(Request $request, Product $product)
    {       
        $user = Auth::user();
        if (!$user || !$user->isStaff() || empty($user->staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // If this user is not admin and trying to view a product doesn't belong to him, then throw error
        if (!$user->staff->isAdmin() && $product->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.product.list')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        // Turn the product into array and unserialize the price configs
        $customer = $product->customer;
        $product = collect($product)->toArray();
        $product['price_configs'] = !empty($product['price_configs']) ? unserialize($product['price_configs']) : [];

        // Get all the current product groups
        $allProductGroups = $this->getAllProductGroups();

        // Format the product groups so we can map them in view
        $allProductGroups = collect($allProductGroups)->mapWithKeys(function($group, int $index) {
            return [$group['id'] => $group['name']];
        });

        return view('admin.product.show', [
            'product' => $product,
            'productGroups' => $allProductGroups,
            'productStatuses' => Product::MAP_STATUSES,
            'units' => Product::UNITS,
            'productStatusColors' => Product::MAP_STATUSES_COLOR,
        ]);
    }

    /** Display the page for edit product details */
    public function edit(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user || !$user->isStaff() || empty($user->staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // If this user is not admin and trying to view a product doesn't belong to him, then throw error
        if (!$user->staff->isAdmin() && $product->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.product.list')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Turn the product into array and unserialize the price configs
        $customer = $product->customer;
        $product = $product->toArray();
        $product['price_configs'] = !empty($product['price_configs']) ? unserialize($product['price_configs']) : [];

        // Format the customers list for searchable dropdowns
        $customersList = $this->formatCustomersList();

        // Get all the current product groups
        $allProductGroups = $this->getAllProductGroups();

        // Format the product groups so we can map them in view
        $allProductGroups = collect($allProductGroups)->mapWithKeys(function($group, int $index) {
            return [$group['id'] => $group['name']];
        });

        return view('admin.product.edit', [
            'product' => $product,
            'customersList' => $customersList,
            'productGroups' => $allProductGroups,
            'productStatuses' => Product::MAP_STATUSES,
            'units' => Product::UNITS,
            'productStatusColors' => Product::MAP_STATUSES_COLOR,
        ]);
    }

    /** Handle request for create new product */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.product.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newProduct = new Product($data);
        if (!$newProduct->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.product.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.product.create.form')->with(['response' => $responseData]);
    }

    /** Handle request for edit product details */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user || !$user->isStaff() || empty($user->staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // If this user is not admin and trying to view a product doesn't belong to him, then throw error
        if (!$user->staff->isAdmin() && $product->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.product.list')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.product.edit.form', ['product' => $product->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        if (!$product->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.product.edit.form', ['product' => $product->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.product.show', ['product' => $product->id])->with(['response' => $responseData]);
    }    

    /** Handle request delete product */
    public function destroy(Request $request, Product $product)
    {         
        // Perform deletion
        if (!$product->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.product.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.product.list')->with(['response' => $responseData]);
    }
    
    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "group_id" => ["required", "integer", "exists:App\Models\Product\Group,id"],
            "customer_id" => ["required", "integer", "exists:App\Models\Customer,id"],
            "name" => ["required"],
            "price" => ["required", "numeric"],
            "stock" => ["required", "integer"],
            "status" => ["required", "integer", Rule::in(Product::PRODUCT_STATUSES)],
            "unit" => ["required", Rule::in(Product::UNITS)],
        ]);

        return $validator;
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

        $data['price_configs'] = serialize([
            'price' => $data['price'],
            'type' => 'unit',
            'unit' => $data['unit'] ?? 'VND',
        ]);

        return collect($data)->only([
            'group_id',
            'customer_id',
            'name',
            'description',
            'stock',
            'price_configs',
            'status',
            'note',
        ])->toArray();
    }

    /** Get all current product groups */
    private function getAllProductGroups()
    {
        $allProductGroups = ProductGroup::all();

        return !empty($allProductGroups)
            ? collect($allProductGroups)->except(['created_at', 'updated_at'])->toArray()
            : [];
    }
    
    /** Format the array for customers list */
    private function formatCustomersList(string $listType = '')
    {
        $user = Auth::user();

        $filterStatuses = (!empty($listType) && $listType  == "all") ? Customer::CUSTOMER_STATUSES : [
            Customer::STATUS_ACTIVE,
            Customer::STATUS_PENDING,
        ];

        $allCustomers = Customer::whereIn('status', $filterStatuses);

        if (!$user->staff->isAdmin()) {
            $alLCustomers = $allCustomers->where('staff_id', $user->staff->id);
        }

        $allCustomers = $allCustomers->select('id', 'full_name', 'customer_id')->get();
        
        $data = [];
        foreach ($allCustomers as $customer) {
            $data[$customer['id']] = "{$customer['full_name']} ({$customer['customer_id']})";
        }

        return $data;
    }
}
