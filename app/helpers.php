<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Models\Order;

if (!function_exists('generateRandomString')) {
    function generateRandomString(int $outputLength = 5)
    {
        $allChars = 'abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ1234567890';

        $output = '';

        for ($i = 0; $i < $outputLength; $i++) {
            $output .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return $output;
    }
}

if (!function_exists('getFormattedFulfillmentsList')) {
    function getFormattedFulfillmentsList()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Formatted list for customer
        if ($user->isCustomer()) {
            $allFulfillments = $user->customer->fulfillments;
            if (!$allFulfillments) {
                return [];
            }

            $data = collect($allFulfillments)->mapWithKeys(function($fulfillment, $index) {
                $fulfillmentArray = collect($fulfillment)->toArray();

                return [$fulfillmentArray['id'] => "Fulfillment #{$fulfillmentArray['id']}",];
            })
            ->toArray();

            return $data;
        }
            
        // Formatted list for staff
        $allFulfillments = Fulfillment::with('customer')
                ->get();

        $data = [];
        foreach ($allFulfillments as $fulfillment) {
            $data[$fulfillment['id']] = "Fulfillment #{$fulfillment['id']} ({$fulfillment['customer']['customer_id']})";
        }

        return $data;
    }
}

if (!function_exists('getFormattedOrdersList')) {
    function getFormattedOrdersList()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Formatted list for customer
        if ($user->isCustomer()) {
            $allOrders = $user->customer->orders;
            if (!$allOrders) {
                return [];
            }

            $data = collect($allOrders)->mapWithKeys(function($order, $index) {
                $orderArray = collect($order)->toArray();

                return [$orderArray['id'] => "Order #{$orderArray['id']}",];
            })
            ->toArray();

            return $data;
        }
            
        // Formatted list for staff
        $allOrders = Order::with('customer')
                ->get();

        $data = [];
        foreach ($allOrders as $order) {
            $data[$order['id']] = "Order #{$order['id']} ({$order['customer']['customer_id']})";
        }

        return $data;
    }
}