<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class AuthController extends Controller
{
    public function index()
    {
        // Navigate to login page if user not logged in
        return redirect()->route('admin.login.form');
    }

    public function loginView()
    {
        // Navigate to login page if user not logged in
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {        
        $data = $request->all();
        // Validate the request
        $validation = Validator::make($data, [
            'username' => ["required", "regex:'^[a-zA-Z0-9]'"], // only allow normal string
            'password' => ["required"]
        ]);

        if ($validation->fails()) {
            return apiResponseFormat()->error()->data($validation->messages()->toArray())->message("Failed to validate request inputs.")->send();
        }
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        try {
            // Authenticate user's details
            $loginAttempt = Auth::attempt($credentials);
        } catch (Exception $e) {
            return apiResponseFormat()->error()->data([
                'errorMessage' => $e->getMessage(),
            ])->message("Some error occurred. Please try again later.")->send();
        }

        if (!$loginAttempt) {
            return back()->withErrors("Your username and password do not match our record.");
        }

        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        try{
            Session::flush();
            Auth::logout();
        } catch (Exception $e) {
            return back()->withErrors("Unable to logout. Please try again later");
        }

        return redirect()->route('admin.index');
    }

    public function registerView()
    {
        if (Auth::check()) {
            $userLoggedIn = Auth::user();
            // We only allow user with level DIRECTOR to register a new user
            if ($userLoggedIn->level != User::LEVEL_DIRECTOR) {
                return redirect()->route('admin.dashboard');
            }

            // Navigate to login page if user not logged in
            return view('client.register');
        }
    }

    public function register(Request $request)
    {
        $data = $request->all();
        // Validate the request
        $validation = Validator::make($data, [
            'username' => ["required", "regex:'^[a-zA-Z0-9]'"], // only allow normal string
            'password' => ["required"],
            'confirm_password' => ["required"],
            'staff_id' => ["required", "regex:'^\d+$'"] // only allow numbers
        ]);

        if ($validation->fails()) {
            return apiResponseFormat()->error()->data($validation->messages()->toArray())->message("Failed to validate request inputs.")->send();
        }

        if ($data['password'] != $data['confirm_password']) {
            return apiResponseFormat()->error()->data([
                'confirm_password' => "Password and confirm password do not match.",
            ])->message("Failed to validate request inputs.")->send();
        }

        if (User::where('username', $data['username'])) {
            return apiResponseFormat()->error()->data([
                'username' => "Username already existed",
            ])->message('A user with this username already existed.')->send();            
        }

        // Validate if id is valid
        $validateTarget = Staff::find($data['staff_id']);

        if (!$validateTarget) {
            return apiResponseFormat()->error()->data([
                'staff_id' => "Invalid staff selected",
            ])->message('Unable to retrieve staff with this ID.')->send();
        }

        $newUser = new User([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'target' => User::TYPE_STAFF,
            'target_id' => $data['staff_id'],
            'level' => User::LEVEL_DIRECTOR,
            'status' => User::STATUS_ACTIVE,
            'note' => $data['note'] ?? '',
        ]);
        $newUser->save();
        
        return apiResponseFormat()->error()->data($newUser)->message("Successfully created new user.")->send();
    }
}
