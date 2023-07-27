<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** Display page for create new user */
    public function create(Request $request)
    {
        return view('admin.user.create', [            
            'userTypes' => User::MAP_TARGETS,
            'userStatuses' => User::MAP_STATUSES,
            'userStaffLevels' => User::MAP_USER_STAFF_LEVELS,
        ]);
    }

    /** Display page for show user details */
    public function show(Request $request, User $user)
    {        
        $owner = $this->getUserOwner($user);

        return view('admin.user.show', [    
            'user' => $user->toArray(),
            'owner' => $owner,        
            'userTypes' => User::MAP_TARGETS,
            'userStatuses' => User::MAP_STATUSES,
            'userStaffLevels' => User::MAP_USER_STAFF_LEVELS,
        ]);

    }

    /** Display page for edit user details */
    public function edit(Request $request, User $user)
    {
        $owner = $this->getUserOwner($user);

        return view('admin.user.edit', [    
            'user' => $user->toArray(),
            'owner' => $owner,        
            'userTypes' => User::MAP_TARGETS,
            'userStatuses' => User::MAP_STATUSES,
            'userStaffLevels' => User::MAP_USER_STAFF_LEVELS,
        ]);
    }

    public function update(Request $request, User $user)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request, $user->username, 'update');
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.user.edit.form', ['user' => $user->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request, 'update');
        if (!$user->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.user.edit.form', ['user' => $user->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.user.show', ['user' => $user->id])->with(['response' => $responseData]);
    }

    /** Handle create new user request */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.user.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newUser = new User($data);
        if (!$newUser->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.user.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.user.create.form')->with(['response' => $responseData]);
    }

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request, string $username = '', string $action = '')
    {
        $validator = Validator::make($request->all(), [
            "username" => empty($username) 
                            ? ["required", "unique:App\Models\User,username"] 
                            : ["required", Rule::unique('App\Models\User')->ignore($username, 'username')],
            "target" => ["required", Rule::in(User::USER_TARGETS)],
            "status" => ["required", "integer", Rule::in(User::USER_STATUSES)],
            "level" => ["required", "integer", Rule::in(User::USER_LEVELS)],
            "password" => empty($action) && $action == 'update' ? ["required"] : "",
            "confirm_password" => empty($action) && $action == 'update' ? ["required", "same:password"] : "",
        ]);        

        $validator->sometimes(
            'staff_id', 
            ["required", "integer", "exists:App\Models\Staff,id"],
            function ($input) {
                return $input->target == User::TARGET_STAFF;
            }
        );        

        $validator->sometimes(
            'customer_id', 
            ["required", "integer", "exists:App\Models\Customer,id"],
            function ($input) {
                return $input->target == User::TARGET_CUSTOMER;
            }
        );        

        $validator->sometimes(
            'supplier_id', 
            ["required", "integer", "exists:App\Models\Supplier,id"],
            function ($input) {
                return $input->target == User::TARGET_SUPPLIER;
            }
        );

        return $validator;
    }

    /** Format the data before saving to database */
    private function formatRequestData(Request $request, string $action = '')
    {
        $data = $request->all();

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            if (in_array($key, ['staff_id', 'customer_id', 'supplier_id'])) {
                $data[$key] = 0;
                continue;
            }

            $data[$key] = "";
        }

        if (empty($action) && $action == 'update') {
            $data['password'] = Hash::make($data['password']);
        }

        return collect($data)->only([
            'username',
            'password',
            'target',
            'level',
            'note',
            'status',
            'staff_id',
            'customer_id',
            'supplier_id',
        ])->toArray();
    }

    /** Get user owner based on target */
    private function getUserOwner(User $user)
    {
        $owner = [];
        switch ($user->target) {
            case User::TARGET_STAFF:
                $staff = $user->staff;
                $owner = collect($staff)->only([
                    'id',
                    'full_name',
                    'position',
                    'status',
                ])->toArray();
                $owner['position'] = Staff::MAP_POSITIONS[$owner['position']];
                $owner['status'] = Staff::MAP_STATUSES[$owner['status']];

                break;
            case User::TARGET_CUSTOMER:
                $customer = $user->customer;
                $owner = collect($customer)->only([
                    'id',
                    'full_name',
                    'customer_id',
                    'status',
                ])->toArray();
                $owner['status'] = Customer::MAP_STATUSES[$owner['status']];

                break;
            case User::TARGET_SUPPLIER:
                $customer = $user->customer;
                $owner = collect($customer)->only([
                    'id',
                    'full_name',
                    'type',
                    'status',
                ])->toArray();
                $owner['type'] = Supplier::MAP_TYPES[$owner['type']];
                $owner['status'] = Supplier::MAP_STATUSES[$owner['status']];

                break;
        }

        return $owner;
    }
}
