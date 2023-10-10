<?php

namespace App\Http\Middleware\Supplier;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Enums\ResponseMessageEnum;
use App\Enums\SupplierEnum;
use Illuminate\Support\Str;

class SupplierPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userLevel)
    {
        $user = Auth::user();
        if (!$user) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::LOGIN_REQUIRED)->send();

            // Redirect
            return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
        }

        if ($user->status != User::STATUS_ACTIVE || $user->supplier->status != SupplierEnum::STATUS_CURRENT) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            // Redirect
            return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
        }

        if ($user->target != User::TARGET_SUPPLIER) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            // Redirect
            return redirect()->route('supplier.login.form')->with(['response' => $responseData]);
        }

        // If this is for all suppliers, we just let them through
        if ($userLevel == "all") {
            return $next($request);
        }

        // If it doesn't contain "|" that means we only validate with one level
        if (!Str::contains($userLevel, '|')) {
            if ($user->level != $userLevel) {
                // Set error message
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();
    
                // Redirect
                return redirect()->back()->with(['response' => $responseData]);
            }

            return $next($request);
        }

        // Otherwise we need to check if current user's level is within the accept range
        $allowedList = explode("|", $userLevel);
        if (!in_array($user->level, $allowedList)) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            // Redirect
            return redirect()->back()->with(['response' => $responseData]);
        }

        return $next($request);
    }
}
