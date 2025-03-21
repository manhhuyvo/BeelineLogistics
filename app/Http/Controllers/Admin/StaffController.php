<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /** 
     * Return view list of all staffs
     */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allStaffs = Staff::with('account');

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || $key == 'page' || $key == '_method') {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allStaffs = $allStaffs->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allStaffs = $allStaffs->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allStaffs = $allStaffs->appends(request()->except('page'));        
        $returnData = collect($allStaffs)->only('data')->toArray();
        $paginationData = collect($allStaffs)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.staff.list', [
            'staffs' => $returnData,
            'pagination' => $paginationData,
            'staffTypes' => Staff::MAP_TYPES,
            'staffPositions' => Staff::MAP_POSITIONS,
            'staffStatuses' => Staff::MAP_STATUSES,
            'staffStatusColors' => Staff::MAP_STATUSES_COLOR,
            'request' => $data,
        ]);
    }

    /**
     * Return view of one single staff
     */
    public function show(Request $request, Staff $staff)
    {   
        // Get and validate staff data     
        $data = collect($staff)->toArray();
        $staff->salary_configs = !empty($staff->salary_configs) ? unserialize($data['salary_configs']) : []; // turn salary configs to array

        return view('admin.staff.show', [
            'staff' => $staff->toArray(),            
            'staffPositions' => Staff::MAP_POSITIONS,
            'staffStatuses' => Staff::MAP_STATUSES,
            'staffTypes' => Staff::MAP_TYPES,
            'staffStatusColors' => Staff::MAP_STATUSES_COLOR,
            'staffCommissionUnits' => Staff::MAP_COMMISSION_UNITS,
            'staffCommissionTypes' => Staff::MAP_COMMISSION_TYPES,
        ]);
    }

    /**
     * Return view of create new staff form
     */
    public function create(Request $request)
    {
        //return session()->get('request');
        return view('admin.staff.create', [            
            'staffPositions' => Staff::MAP_POSITIONS,
            'staffStatuses' => Staff::MAP_STATUSES,
            'staffTypes' => Staff::MAP_TYPES,
            'staffCommissionUnits' => Staff::MAP_COMMISSION_UNITS,
            'staffCommissionTypes' => Staff::MAP_COMMISSION_TYPES,
        ]);
    }

    /**
     * Return view of edit a staff form
     */
    public function edit(Request $request, Staff $staff)
    {
        // Get and validate staff data     
        $data = collect($staff)->toArray();
        $staff->salary_configs = !empty($staff->salary_configs) ? unserialize($data['salary_configs']) : []; // turn salary configs to array

        return view('admin.staff.edit', [ 
            'staff' => $staff->toArray(),            
            'staffPositions' => Staff::MAP_POSITIONS,
            'staffStatuses' => Staff::MAP_STATUSES,
            'staffTypes' => Staff::MAP_TYPES,
            'staffStatusColors' => Staff::MAP_STATUSES_COLOR,
            'staffCommissionUnits' => Staff::MAP_COMMISSION_UNITS,
            'staffCommissionTypes' => Staff::MAP_COMMISSION_TYPES,
        ]);
    }

    /**
     * Handle CREATE new staff request
     */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.staff.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        
        $newStaff = new Staff($data);
        if (!$newStaff->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.staff.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newStaff->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.staff.create.form')->with(['response' => $responseData]);
    }

    /**
     * Handle UPDATE a staff request
     */
    public function update(Request $request, Staff $staff)
    {     
        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.staff.edit.form', ['staff' => $staff->id])->with(['response' => $responseData]);
        }
        
        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        if (!$staff->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.staff.edit.form', ['staff' => $staff->id])->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($staff->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.staff.show', ['staff' => $staff->id])->with(['response' => $responseData]);
    }

    /**
     * Handle DELETE a staff request
     */
    public function destroy(Request $request, Staff $staff)
    {         
        // Perform deletion
        if (!$staff->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.staff.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($staff->toArray())->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.staff.list')->with(['response' => $responseData]);
    }

    /** Validate the request for store or update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "full_name" => ["required"],
            "phone" => ["required"],
            "address" => ["required"],
            "base_salary" => ["required", "numeric"],
            "position" => ["required", "integer"],
            "type" => ["required", "integer"],
            "dob" => ["required"],
            "status" => ["required", "integer"],
        ]);

        $validator->sometimes('commission_amount', ["required", "numeric"], function ($input) {
            return $input->apply_commission != null;
        });

        return $validator;
    }

    /** Format the request data before saving to database */
    private function formatRequestData(Request $request)
    {

        $data = $request->all();
        if (Str::contains($data['base_salary'], [',', '.'])) {
            $data['base_salary'] = Str::remove([',', '.'], $data['base_salary']);
        }

        // Add base salary to list first
        $salaryConfigs = [
            'base_salary' => $data['base_salary'],
        ];

        if (!empty($data['apply_commission']) && $data['apply_commission'] == 'on') {
            if (empty($data['commission_amount']) || empty($data['commission_type']) || empty($data['commission_unit'])) {
                return false;
            }

            if (Str::contains($data['commission_amount'], ',')) {
                $data['commission_amount'] = Str::replace(',', '.', $data['commission_amount']);
            }
            $commissionData = [
                'commission_amount' => $data['commission_amount'],
                'commission_unit' => $data['commission_unit'],
                'commission_type' => $data['commission_type'],
            ];

            $salaryConfigs = array_merge(
                ['base_salary' => $data['base_salary']],
                ['commission' => $commissionData]
            );
        }

        // Serialize the salary configs
        $data['salary_configs'] = serialize($salaryConfigs);
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
            'salary_configs',
            'dob',
            'position',
            'type',
            'status',
            'note',
        ])->toArray();
    }
}
