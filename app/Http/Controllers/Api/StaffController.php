<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseStatusEnum;
use App\Models\Helpers\ApiResponseFormat;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class StaffController extends Controller
{
    /** 
     * Return view list of all staffs
     */
    public function index()
    {
        $allStaffs = Staff::all();

        if ($allStaffs->isEmpty()) {
            return apiResponseFormat()->error()->message('There is no staff to view.')->send();
        }

        return apiResponseFormat()->success()->data($allStaffs)->message('Successfully retrieve list of staffs.')->send();
    }

    /**
     * Return view of one single staff
     */
    public function show(string $staff)
    {        
        $thisStaff = Staff::where('id', $staff)->first();

        if (!$thisStaff) {
            return apiResponseFormat()->error()->message('Unable to retrieve staff with this ID.')->send();
        }

        return apiResponseFormat()->success()->data($thisStaff)->message('Successfully retrieve staff details.')->send();
    }

    /**
     * Return view of create new staff form
     */
    public function create()
    {

    }

    /**
     * Return view of edit a staff form
     */
    public function edit(string $staff)
    {

    }

    /**
     * Handle CREATE new staff request
     */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
        if ($validation->fails()) {
            return apiResponseFormat()->error()->data($validation)->message("Failed to validate request inputs.")->send();
        }

        // We only want to take necessary fields
        $data = $this->sanitizeInputs($request);

        return apiResponseFormat()->success()->message("Successfully create new staff.")->send();
    }

    /**
     * Handle UPDATE a staff request
     */
    public function update(Request $request, string $staff)
    {        
        // Find current staff details
        $currentStaff = Staff::where('id', $staff)->first();
        if (!$currentStaff) {
            return apiResponseFormat()->error()->message('Unable to retrieve staff with this ID.')->send();
        }

        // Validate the request coming
        $validation = $this->validateRequest($request);
        if ($validation->fails()) {
            return apiResponseFormat()->error()->data($validation)->message("Failed to validate request inputs.")->send();
        }
        
        // We only want to take necessary fields
        $data = $this->sanitizeInputs($request);

        return apiResponseFormat()->success()->message("Successfully update staff details.")->send();
    }
    /**
     * Handle DELETE a staff request
     */
    public function destroy(string $staff)
    {                
        // Find current staff details
        $currentStaff = Staff::where('id', $staff)->first();
        if (!$currentStaff) {
            return apiResponseFormat()->error()->message('Unable to retrieve staff with this ID.')->send();
        }

        // Perform deletion
        if (!$currentStaff->delete()) {
            return apiResponseFormat()->error()->message('Failed to delete staff.')->send();
        }

        return apiResponseFormat()->success()->message("Successfully delete staff #{$staff} ({$currentStaff->full_name})")->send();
    }

    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            "full_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "salary_configs" => ["require"],
            "address" => ["required"],
            "position" => ["required"],
            "dob" => ["required"],
            "status" => ["required", "in_array:". Staff::STAFF_STATUSES],
        ]);
    }

    private function sanitizeInputs(Request $request)
    {
        return $request->collect([
            'full_name',
            'phone',
            'address',
            'salary_configs',
            'dob',
            'position',
            'status',
            'note',
        ]);
    }
}
