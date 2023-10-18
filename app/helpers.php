<?php

use App\Enums\SupplierEnum;
use App\Enums\SupplierMetaEnum;
use App\Enums\CustomerMetaEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Models\Order;
use App\Models\Staff;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;

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

        if (!$user->staff->isAdmin()) {
            $allFulfillments = Fulfillment::with('customer')
                    ->whereHas('customer', function($query) use ($user) {
                        $query->where('staff_id', $user->staff->id);
                    })
                    ->get();
        } else {
            $allFulfillments = Fulfillment::with('customer')
                    ->get();
        }

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
        if (!$user->staff->isAdmin()) {
            $allOrders = Order::with('customer')
                    ->where('staff_id', $user->staff->id)
                    ->orWhereHas('customer', function($query) use ($user) {
                        $query->where('staff_id', $user->staff->id);
                    })
                    ->get();
        } else {
            $allOrders = Order::with('customer')
                    ->get();
        }

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

        if (!$user->staff->isAdmin()) {
            $allCustomers = Customer::where('staff_id', $user->staff->id);
            if (!$active) {
                $allCustomers = $allCustomers->get();
            } else {
                $allCustomers = $allCustomers->where('status', Customer::STATUS_ACTIVE)->get();
            }
        } else {
            if (!$active) {
                $allCustomers = Customer::all();
            } else {
                $allCustomers = Customer::where('status', Customer::STATUS_ACTIVE)->get();
            }
        }

        $data = [];
        foreach ($allCustomers as $customer) {
            $data[$customer['id']] =  "{$customer['customer_id']} {$customer['full_name']}";
        }

        return $data;
    }
}

if (!function_exists('getFormattedSuppliersList')) {
    function getFormattedSuppliersList(bool $active = false) {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Return empty list if this is not a staff
        if (!$user->isStaff() || !$user->staff->isAdmin()) {
            return [];
        }

        // Get the supplier list
        if (!$active) {
            $allSuppliers = Supplier::all();
        } else {
            $allSuppliers = Supplier::where('status', SupplierEnum::STATUS_CURRENT)->get();
        }

        $data = [];
        foreach ($allSuppliers as $supplier) {
            $company = $supplier['company'] ?? 'Company Unknown';

            // Get country meta
            $countryMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY);

            if (!$countryMeta) {
                $data[$supplier['id']] = "{$supplier['full_name']} [{$company}]";
                continue;
            }

            // Get the list of countries formatted as a string
            $countries = $countryMeta->getFormattedValue();

            $data[$supplier['id']] = "{$supplier['full_name']} [{$company}]";
        }

        return $data;
    }
};

if (!function_exists('getFormattedCustomersListForSupplier')) {
    function getFormattedCustomersListForSupplier(bool $active = false)
    {
        $user = Auth::user();        

        // Formatted list for supplier
        $countries = $user->supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY)->getValue();
        $services = $user->supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_SERVICE)->getValue();

        // If supplier is missing meta, them return empty
        if (empty($countries) || empty($services)) {
            return [];
        }

        if ($active) {
            $allCustomers = Customer::has('meta')->where('status', Customer::STATUS_ACTIVE)->get();
        } else {
            $allCustomers = Customer::has('meta')->get();
        }

        $returnData = [];
        foreach ($allCustomers as $customer) {
            $customerCountries = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_COUNTRY)->getValue();
            $countryMatched = collect($customerCountries)
                    ->filter(function($country) use ($countries) {
                        return in_array($country, $countries);
                    });
            
            // If there is no countries matched, then skip this customer
            if ($countryMatched->isEmpty()) {
                continue;
            }
            
            $customerServices = $customer->getMeta(CustomerMetaEnum::META_AVAILABLE_SERVICE)->getValue();
            $serviceMatched = collect($customerServices)
                    ->filter(function($service) use ($services) {
                        return in_array($service, $services);
                    });
            
            // If there is no services matched, then skip this customer
            if ($serviceMatched->isEmpty()) {
                continue;
            }

            $returnData[$customer->id] = "{$customer->customer_id} {$customer->full_name}";
        }

        return $returnData;
    }
}