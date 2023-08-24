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
use Illuminate\Validation\Rule;

class FulfillmentController extends Controller
{
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

    public function store(Request $request)
    {
        return $request->all();
    }

    /** Format the array for products list */
    private function formatProductsList()
    {
        // Load all products with their groups
        $allProducts = Product::with('productGroup')->get();
        // Get group name and filter the un-used keys
        return collect($allProducts)->map(function(Product $product) {
            $product['group_name'] = $product->productGroup->name ?? '';

            return collect($product)->only(['id', 'group_id', 'group_name', 'name', 'stock', 'status'])->toArray();
        })->toArray();
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
