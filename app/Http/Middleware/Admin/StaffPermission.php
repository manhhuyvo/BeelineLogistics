<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StaffPermission
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
            return back()->withErrors("Please login to your account.");
        }

        if ($user->target != User::TYPE_STAFF) {
            return back()->withErrors("You don't have permission to access this page");
        }

        if ($user->level != $userLevel) {
            return back()->withErrors("You don't have permission to access this page");
        }

        return $next($request);
    }
}
