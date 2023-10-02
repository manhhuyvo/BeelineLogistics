<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Enums\SupportTicketEnum;
use Illuminate\Support\Facades\Auth;

class SmallElementsLoader extends Controller
{
    public function getNewproductRow()
    {
        $productsList = $this->formatProductsList();

        return view('customer.small_elements.product-row', [
            'productsList' => $productsList,
        ]);
    }

    public function getNewTicketBelongsRow($target)
    {
        if (!empty($target) && $target == SupportTicketEnum::TARGET_FULFILLMENT) {
            $targetsList = getFormattedFulfillmentsList();
        } else if (!empty($target) && $target == SupportTicketEnum::TARGET_ORDER) {
            $targetsList = getFormattedOrdersList();
        }
        
        return view('customer.small_elements.ticket-belongs-row', [
            'targetsList' => $targetsList ?? [],
            'target' => $target,
        ]);
    }

    /** Format the array for products list */
    private function formatProductsList()
    {
        // Load all products with their groups
        $user = Auth::user();
        $customer = $user->customer;
        $allProducts = $customer->products;
        
        // Get group name and filter the un-used keys
        $allProducts = collect($allProducts)->mapWithKeys(function(Product $product, int $index) {
            $product['group_name'] = $product->productGroup->name ?? '';
            $product = collect($product)->only(['id', 'group_id', 'group_name', 'name', 'stock', 'status'])->toArray();
            $formattedStatus = Product::MAP_STATUSES[$product['status']];

            return [$product['id'] => "{$product['name']} - Group: {$product['group_name']} - Stock: {$product['stock']} ({$formattedStatus})"];
        })->toArray();

        return $allProducts;
    }
}
