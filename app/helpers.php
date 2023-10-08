<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Models\Order;
use App\Models\Staff;
use App\Models\User;
use App\Models\Customer;

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

                return [$fulfillmentArray['id'] => "Fulfillment #{$fulfillmentArray['id']} - Name: " . htmlspecialchars($fulfillmentArray['name']),];
            })
            ->toArray();

            return $data;
        }
            
        // Formatted list for staff
        $allFulfillments = Fulfillment::with('customer')
                ->get();

        $data = [];
        foreach ($allFulfillments as $fulfillment) {
            $data[$fulfillment['id']] = "Fulfillment #{$fulfillment['id']} " . htmlspecialchars($fulfillment['name']) . " ({$fulfillment['customer']['customer_id']})";
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

if (!function_exists('getFormattedStaffsList')) {
    function getFormattedStaffsList()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Return empty list if this is not a staff
        if (!$user->isStaff()) {
            return [];
        }

        // Get the staffs list (only apply for staff)
        $allStaffs = Staff::all();

        $data = [];
        foreach ($allStaffs as $staff) {
            $data[$staff['id']] =  "{$staff['full_name']} (" . Staff::MAP_POSITIONS[$staff['position']] . ")";
        }

        return $data;
    }
}

if (!function_exists('getFormattedUsersListOfStaff')) {
    function getFormattedUsersListOfStaff()
    {
        
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Return empty list if this is not a staff
        if (!$user->isStaff()) {
            return [];
        }

        // Get the staffs list (only apply for staff)
        $allUsers = User::whereHas('staff', function($query) {
            $query->where('status', '=', Staff::STATUS_CURRENT);
        })->get();

        $data = [];
        foreach ($allUsers as $user) {
            $data[$user['id']] =  "{$user['staff']['full_name']} (" . Staff::MAP_POSITIONS[$user['staff']['position']] . ")";
        }

        return $data;
    }
}

if (!function_exists('getFormattedCustomersList')) {
    function getFormattedCustomersList(bool $active = false)
    {        
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Return empty list if this is not a staff
        if (!$user->isStaff()) {
            return [];
        }

        // Get the customers list (only apply for staff)
        if (!$active) {
            $allCustomers = Customer::all();
        } else {
            $allCustomers = Customer::where('status', Customer::STATUS_ACTIVE)->get();
        }

        $data = [];
        foreach ($allCustomers as $customer) {
            $data[$customer['id']] =  "{$customer['customer_id']} {$customer['full_name']}";
        }

        return $data;
    }
}