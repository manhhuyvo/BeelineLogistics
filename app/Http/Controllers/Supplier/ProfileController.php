<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Enums\ResponseMessageEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Supplier;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $supplier = $user->supplier;

        // Get and validate staff data
        $supplier = collect($user->supplier)->toArray();

        return view('supplier.profile.index', [
            'user' => $user,
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $supplier = $user->supplier;

        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        
        // If update not successful, we return error
        if (!$supplier->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
        }

        // Otherwise display successful message
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);

    }

    public function changePassword(Request $request)
    {
        // Data validation
        $validation = $this->validateChangePasswordRequest($request);

        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
        }

        $data = $request->all();

        // Check if password matches with confirm password
        if ($data['new_password'] != $data['confirm_password']) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::CONRIM_PASSWORD_NOT_MATCH)->send();

            return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
        }

        // Get the current user for update
        $user = Auth::user();

        $updateCredential = [
            'password' => Hash::make($data['new_password']),
        ];

        if (!$user->update($updateCredential)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
        }

        // Otherwise display successful message
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('supplier.user.profile.form')->with(['response' => $responseData]);
    }

    /** Validate the update request */
    private function validateRequest(Request $request)
    {        
        return Validator::make($request->all(), [
            // Personal Details
            "full_name" => ["required"],
            "phone" => ["required"],
            "address" => ["required"],
        ]);
    }

    /** Validate the change password request */
    private function validateChangePasswordRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'new_password' => ["required"],
            'confirm_password' => ["required"],
        ]);

    }

    /** Format the data before saving to database */
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
            'full_name',
            'phone',
            'address',
            'company',
        ])->toArray();
    }
}
