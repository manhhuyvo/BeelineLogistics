<?php

namespace App\Http\Middleware\Customer;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\View;
use App\Enums\ResponseMessageEnum;
use Illuminate\Support\Facades\Session;
use Exception;

class CustomerLoginRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // If no user logged in, then we allow access to login page
        if (!Auth::check()) {
            return $next($request);
        }

        // We only allow customer to login to customer portal
        $user = Auth::user();

        if ($user->target != User::TARGET_CUSTOMER || $user->status != User::STATUS_ACTIVE || $user->customer->status != Customer::STATUS_ACTIVE) {
            try{
                Session::flush();
                Auth::logout();
            } catch (Exception $e) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return redirect()->back()->with(['response' => $responseData]);
            }
    
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();
    
            return redirect()->route('customer.login.form')->with(['response' => $responseData]);
        }

        View::share([ 'user' => $user]);
        return redirect()->route('customer.dashboard');
    }
}
