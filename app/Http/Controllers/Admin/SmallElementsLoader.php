<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Enums\InvoiceEnum;
use App\Models\Fulfillment;
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

    public function getNewInvoiceRow($target)
    {
        $customersList = $this->formatCustomersList();
        if (!empty($target) && $target == InvoiceEnum::TARGET_MANUAL) {
            return view('admin.small_elements.invoice-row', [
                'customersList' => $customersList,
                'target' => $target,
                'createInvoiceFrom' => InvoiceEnum::MAP_TARGETS,
            ]);
        }

        if (!empty($target) && $target == InvoiceEnum::TARGET_FULFILLMENT) {
            $targetsList = $this->formatFulfillmentsList();
        } else if (!empty($target) && $target == InvoiceEnum::TARGET_ORDER) {
            $targetsList = $this->formatOrdersList();
        }
        
        return view('admin.small_elements.invoice-row', [
            'customersList' => $customersList,
            'targetsList' => $targetsList,
            'target' => $target,
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
            $displayMessage = "{$product['name']}";

            if ($product->productGroup) {
                $product['group_name'] = $product->productGroup->name ?? '';
                $displayMessage .= " - Group: {$product['group_name']}";
            }

            if ($product->customer) {
                $product['customer_name'] = "{$product->customer->customer_id} {$product->customer->full_name}";
                $displayMessage .= " - Customer: {$product['customer_name']}";
            }

            $product = collect($product)->only(['id', 'group_id', 'group_name', 'name', 'stock', 'status'])->toArray();
            $formattedStatus = Product::MAP_STATUSES[$product['status']];

            $displayMessage .= " - Stock: {$product['stock']} ($formattedStatus)";

            return [$product['id'] => $displayMessage];
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

    /** Format the array for fulfillments list */
    private function formatFulfillmentsList(string $listType = '')
    {
        $allFulfillments = Fulfillment::with('customer')
                    ->get();
        
        $data = [];
        foreach ($allFulfillments as $fulfillment) {
            $data[$fulfillment['id']] = "Fulfillment #{$fulfillment['id']} ({$fulfillment['customer']['customer_id']})";
        }

        return $data;
    }

    /** Format the array for fulfillments list */
    private function formatOrdersList(string $listType = '')
    {
        $allOrders = Order::with('customer')
                    ->get();
        
        $data = [];
        foreach ($allOrders as $order) {
            $data[$order['id']] = "Order #{$order['id']} ({$order['customer']['customer_id']})";
        }

        return $data;
    }
}
