<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Enums\InvoiceEnum;
use Illuminate\Http\Request;

class SmallElementsLoader extends Controller
{
    public function getNewproductRow()
    {
        $productsList = $this->formatProductsList();

        return view('admin.small_elements.product-row', [
            'productsList' => $productsList,
        ]);
    }

    public function getNewInvoiceRow()
    {
        $customersList = $this->formatCustomersList();

        return view('admin.small_elements.invoice-row', [
            'customersList' => $customersList,
            'createInvoiceFrom' => InvoiceEnum::MAP_TARGETS,
        ]);
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
}
