<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    public function index()
    {
        $allStaffs = Staff::all();

        if ($allStaffs->isEmpty()) {
            return "Nothing here";
        }

        return "Something";
    }

    public function show(string $staff)
    {
        return $staff ?? 'Not passing';
    }
}
