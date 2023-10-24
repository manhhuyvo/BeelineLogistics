<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\SupplierEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class AjaxController extends Controller
{
    // Search User Owner
    public function searchUserOwner(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            // We don't want these data to be returned to AJAX while we don't need it
            return view('admin.user.components.user-empty-owner', ['message' => ResponseMessageEnum::FAILED_VALIDATE_INPUT]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        // Now we search
        switch($data['target']) {
            case User::TARGET_STAFF:
                // Search staff by name
                $result = Staff::where('full_name', 'like', "%{$data['searchTerm']}%")->get();       
                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['position'] = Staff::MAP_POSITIONS[$row['position']] ?? "Unknown";
                        $row['status_color'] = Staff::MAP_STATUSES_COLOR[$row['status']] ?? "";
                        $row['status'] = Staff::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'id',
                            'full_name',
                            'position',
                            'status',
                            'status_color',
                        ]);
                    });

                    $view = view('admin.user.components.user-staff-owner', ['data' => $returnData]);
                }
                break;
            case User::TARGET_CUSTOMER:        
                // Search by customer name or customer_id
                $result = Customer::where('full_name', 'like', "%{$data['searchTerm']}%")
                ->orWhere('customer_id', 'like', "%{$data['searchTerm']}%")
                ->get();    

                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['status_color'] = Customer::MAP_STATUSES_COLOR[$row['status']] ?? '';
                        $row['status'] = Customer::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'id',
                            'customer_id',
                            'full_name',
                            'status',
                            'status_color',
                        ]);
                    });

                    $view = view('admin.user.components.user-customer-owner', ['data' => $returnData]);
                }
                break;
            case User::TARGET_SUPPLIER:
                // Search by supplier name
                $result = Supplier::where('full_name', 'like', "%{$data['searchTerm']}%")->get();        
                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['status_color'] = SupplierEnum::MAP_STATUSES_COLOR[$row['status']] ?? '';
                        $row['type'] = SupplierEnum::MAP_TYPES[$row['type']] ?? "Unknown";
                        $row['status'] = SupplierEnum::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'id',
                            'full_name',
                            'type',
                            'status',
                            'status_color',
                        ]);
                    });

                    $view = view('admin.user.components.user-supplier-owner', ['data' => $returnData]);
                } 
                break;
            default:
            break;
        }

        return $view ?? view('admin.user.components.user-empty-owner', ['message' => 'Cannot find records matched.']);
    }

    public function searchCustomer(Request $request)
    {
        $user = Auth::user();

        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            // We don't want these data to be returned to AJAX while we don't need it
            return view('admin.fulfillment.components.customer-empty', ['message' => ResponseMessageEnum::FAILED_VALIDATE_INPUT]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        // Search by customer name or customer_id
        $result = Customer::where('full_name', 'like', "%{$data['searchTerm']}%")
        ->orWhere('customer_id', 'like', "%{$data['searchTerm']}%");
        if ($user->isStaff() && !$user->staff->isAdmin()) {
            $result = $result->where('staff_id', $user->staff->id);
        }
        $result = $result->get();

        // If result is not empty, then we filter data 
        if (!$result->isEmpty()) {
            $returnData = collect($result)->map(function($row) {
                $row['status_color'] = Customer::MAP_STATUSES_COLOR[$row['status']] ?? '';
                $row['status'] = Customer::MAP_STATUSES[$row['status']] ?? "Unknown";

                return collect($row)->only([
                    'id',
                    'customer_id',
                    'full_name',
                    'status',
                    'status_color',
                ]);
            });

            $view = view('admin.fulfillment.components.customer-result', ['data' => $returnData]);
        }

        return $view ?? view('admin.fulfillment.components.customer-empty', ['message' => 'Cannot find records matched.']);
    }

    public function searchFulfillmentProduct(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            // We don't want these data to be returned to AJAX while we don't need it
            return view('admin.fulfillment.components.customer-empty', ['message' => ResponseMessageEnum::FAILED_VALIDATE_INPUT]);
        }

    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "target" => ["required", Rule::in(User::USER_TARGETS)],
            "searchTerm" => ["required"],
        ]);

        return $validator;
    }

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

        return collect($data)->only([
            "target",
            "searchTerm",
        ]);
    }
}
