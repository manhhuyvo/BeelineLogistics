<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\View;

class StaffLoginRedirect
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

        // We only allow staff to login to admin portal
        $user = Auth::user();
        if ($user->target != User::TYPE_STAFF) {
            return redirect()->route('admin.login');
        }

        View::share([ 'user' => $user]);
        return redirect()->route('admin.dashboard');
    }
}
