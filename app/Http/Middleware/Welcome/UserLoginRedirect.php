<?php

namespace App\Http\Middleware\Welcome;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Exception;
use App\Enums\ResponseMessageEnum;
use App\Enums\SupplierEnum;
use App\Models\Customer;
use App\Models\Staff;

class UserLoginRedirect
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
        // If no user logged in, then we allow access to welcome page
        if (!Auth::check()) {
            return $next($request);
        }

        // Get current logged-in user
        $user = Auth::user();

        // If this user is not active, then we destroy session and throw them to this page here, but let them know that they have been logged out
        if ($user->status != User::STATUS_ACTIVE) {
            try{
                Session::flush();
                Auth::logout();
            } catch (Exception $e) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return $next($request);
            }
    
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();
    
            return $next($request);
        }

        // Otherwise check if this user belogns to which group
        if ($user->isStaff()) {
            if ($user->staff->status == Staff::STATUS_CURRENT) {
                View::share([ 'user' => $user]);

                return redirect()->route('admin.dashboard');
            }

            // If staff is not current, then log them out
            try{
                Session::flush();
                Auth::logout();
            } catch (Exception $e) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return $next($request);
            }
    
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();
    
            return $next($request);
        }

        // Otherwise check if this user belogns to which group
        if ($user->isCustomer()) {
            if ($user->customer->status == Customer::STATUS_ACTIVE) {
                View::share([ 'user' => $user]);

                return redirect()->route('customer.dashboard');
            }

            // If customer is not current, then log them out
            try{
                Session::flush();
                Auth::logout();
            } catch (Exception $e) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return $next($request);
            }
    
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();
    
            return $next($request);
        }

        // Otherwise check if this user belogns to which group
        if ($user->isSupplier()) {
            if ($user->supplier->status == SupplierEnum::STATUS_CURRENT) {
                View::share([ 'user' => $user]);

                return redirect()->route('supplier.dashboard');
            }

            // If supplier is not current, then log them out
            try{
                Session::flush();
                Auth::logout();
            } catch (Exception $e) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return $next($request);
            }
    
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();
    
            return $next($request);
        }

        try{
            Session::flush();
            Auth::logout();
        } catch (Exception $e) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return $next($request);
        }

        $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGOUT_MESSAGE)->send();

        return $next($request);
    }
}
