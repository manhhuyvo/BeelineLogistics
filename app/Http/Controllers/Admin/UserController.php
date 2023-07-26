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

    public function store(Request $request)
    {
        return $request->all();
    }
}
