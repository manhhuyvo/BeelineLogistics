<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /** Display page for create new customer */
    public function create(Request $request)
    {
        return view('admin.supplier.create', [            
            'supplierTypes' => Supplier::MAP_TYPES,
            'supplierStatuses' => Supplier::MAP_STATUSES,
        ]);
    }

    /** Handle request for create new customer */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newSupplier = new Supplier($data);
        if (!$newSupplier->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newSupplier->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.supplier.create.form')->with(['response' => $responseData]);
    }

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "full_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "address" => ["required"],
            "type" => ["required", "integer"],
            "status" => ["required", "integer"],
        ]);

        return $validator;
    }

    /** Format the data before saving to database */
    private function formatRequestData(Request $request)
    {
        return collect($request->all())->only([
            'full_name',
            'phone',
            'address',
            'company',
            'status',
            'type',
            'note',
        ])->toArray();
    }
}
