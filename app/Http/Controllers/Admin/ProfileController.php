<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Enums\ResponseMessageEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $staff = $this->getUserOwner();

        // Get and validate staff data     
        $data = collect($staff)->toArray();
        $staff->salary_configs = !empty($staff->salary_configs) ? unserialize($data['salary_configs']) : []; // turn salary configs to array

        return view('admin.profile.index', [
            'user' => $user,
            'staff' => $staff->toArray(),            
            'staffPositions' => Staff::MAP_POSITIONS,
            'staffStatuses' => Staff::MAP_STATUSES,
            'staffTypes' => Staff::MAP_TYPES,
            'staffStatusColors' => Staff::MAP_STATUSES_COLOR,
            'staffCommissionUnits' => Staff::MAP_COMMISSION_UNITS,
            'staffCommissionTypes' => Staff::MAP_COMMISSION_TYPES,
        ]);
    }

    public function update(Request $request)
    {
        $staff = $this->getUserOwner();

        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
        }

        // Get only the keys that we need
        $data = collect($request->all())->only(['full_name', 'phone', 'dob', 'address'])->toArray();
        
        // If update not successful, we return error
        if (!$staff->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
        }

        // Otherwise display successful message
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
    }

    public function changePassword(Request $request)
    {
        // Data validation
        $validation = $this->validateChangePasswordRequest($request);

        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
        }

        $data = $request->all();

        // Check if password matches with confirm password
        if ($data['new_password'] != $data['confirm_password']) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::CONRIM_PASSWORD_NOT_MATCH)->send();

            return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
        }

        // Get the current user for update
        $user = Auth::user();
        $userModel = User::find($user->id);

        $updateCredential = [
            'password' => Hash::make($data['new_password']),
        ];

        if (!$userModel->update($updateCredential)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
        }

        // Otherwise display successful message
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.user.profile.form')->with(['response' => $responseData]);
    }

    /** Get user's owner model */
    private function getUserOwner()
    {
        $user = Auth::user();

        $staff = $user->staff;

        if (empty($staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->route('admin.dashboard')->with(['response' => $responseData]);
        }

        return $staff;
    }

    /** Validate the update request */
    private function validateRequest(Request $request)
    {        
        return Validator::make($request->all(), [
            "full_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "address" => ["required"],
            "dob" => ["required"],
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
}
