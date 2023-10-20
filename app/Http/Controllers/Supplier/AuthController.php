<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\ResponseMessageEnum;

class AuthController extends Controller
{
    public function index()
    {
        $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGIN_REQUIRED)->send();

        return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
    }

    public function loginView()
    {
        return view('supplier.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->all();
        // Validate the request
        $validation = Validator::make($data, [
            'username' => ["required"], // only allow normal string
            'password' => ["required"]
        ]);

        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
        }

        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
            'status' => User::STATUS_ACTIVE,
        ];

        try {
            // Authenticate user's details
            $loginAttempt = Auth::attempt($credentials);
        } catch (Exception $e) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        if (!$loginAttempt) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::WRONG_CREDENTIALS)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $request->session()->regenerate();
        
        return redirect()->route('supplier.dashboard');
    }

    public function logout()
    {
        try{
            Session::flush();
            Auth::logout();
        } catch (Exception $e) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();

        return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
    }
}
