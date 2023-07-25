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
            return apiResponseFormat()->error()->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        // Now we search
        switch($data['target']) {
            case User::TYPE_STAFF:
                // Search staff by name
                $result = Staff::where('full_name', 'like', "%{$data['searchTerm']}%")->get();       
                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['position'] = Staff::MAP_POSITIONS[$row['position']] ?? "Unknown";
                        $row['status'] = Staff::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'full_name',
                            'position',
                            'status',
                        ]);
                    });
                }
                break;
            case User::TYPE_CUSTOMER:
                // Search by customer name or customer_id
                $result = Customer::where('full_name', 'like', "%{$data['searchTerm']}%")
                ->orWhere('customer_id', 'like', "%{$data['searchTerm']}%")
                ->get();       
                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['status'] = Customer::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'customer_id',
                            'full_name',
                            'status',
                        ]);
                    });
                }
                break;
            case User::TYPE_SUPPLIER:
                // Search by supplier name
                $result = Staff::where('full_name', 'like', "%{$data['searchTerm']}%")->get();        
                // If result is not empty, then we filter data 
                if (!$result->isEmpty()) {
                    $returnData = collect($result)->map(function($row) {
                        $row['type'] = Supplier::MAP_TYPES[$row['type']] ?? "Unknown";
                        $row['status'] = Supplier::MAP_STATUSES[$row['status']] ?? "Unknown";
        
                        return collect($row)->only([
                            'full_name',
                            'type',
                            'status',
                        ]);
                    });
                } 
                break;
            default:
            break;
        }

        //Return message according to data empty or found        
        return apiResponseFormat()
            ->success()
            ->data($returnData ?? [])
            ->message(!empty($returnData) ? ResponseMessageEnum::AJAX_SUCCESS_FOUND : ResponseMessageEnum::AJAX_EMPTY_FOUND)
            ->send();
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "target" => ["required", Rule::in(User::USER_TYPES)],
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
