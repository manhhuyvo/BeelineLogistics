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
    /** Display page for view all users */
    /** Display the page for list of all suppliers */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allUsers = User::whereNotNull('id');

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
                $allUsers = $allUsers->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allUsers = $allUsers->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');

        // Get user's owner
        $owners = [];
        foreach ($allUsers as $user) {
            $owners[$user->id] = $this->getUserOwner($user);
        }

        $allUsers = $allUsers->appends(request()->except('page'));        
        $returnData = collect($allUsers)->only('data')->toArray();
        $paginationData = collect($allUsers)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.user.list', [
            'users' => $returnData,
            'owners' => $owners,
            'pagination' => $paginationData,   
            'userStatusColors' => User::MAP_STATUSES_COLOR,         
            'userTypes' => User::MAP_TARGETS,
            'userStatuses' => User::MAP_STATUSES,
            'userStaffLevels' => User::MAP_USER_STAFF_LEVELS,
            'userLevels' => User::MAP_USER_LEVELS,
            'request' => $data,
        ]);
    }
    
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

    /** Handle request for edit user */
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

    /** Handle request delete supplier */
    public function destroy(Request $request, User $user)
    {         
        // Perform deletion
        if (!$user->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.user.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.user.list')->with(['response' => $responseData]);
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
            "password" => ["required"],
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
