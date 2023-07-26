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
    /** Display page for create new customer */
    public function create(Request $request)
    {
        return view('admin.user.create', [            
            'userTypes' => User::MAP_TARGETS,
            'userStatuses' => User::MAP_STATUSES,
            'userStaffLevels' => User::MAP_USER_STAFF_LEVELS,
        ]);
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
    private function validateRequest(Request $request, string $username = '')
    {
        $validator = Validator::make($request->all(), [
            "username" => empty($username) 
                            ? ["required", "unique:App\Models\User,username"] 
                            : ["required", Rule::unique('App\Models\User')->ignore($username, 'username')],
            "target" => ["required", Rule::in(User::USER_TARGETS)],
            "status" => ["required", "integer", Rule::in(User::USER_STATUSES)],
            "level" => ["required", "integer", Rule::in(User::USER_LEVELS)],
            "password" => ["required"],
            "confirm_password" => ["required", "same:password"],
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
    private function formatRequestData(Request $request)
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
        $data['password'] = Hash::make($data['password']);

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
}
