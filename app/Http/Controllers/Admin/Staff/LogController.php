<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
use App\Enums\UserEnum;
use Illuminate\Support\Str;
use App\Repositories\Staff\LogRepo;

class LogController extends Controller
{   
    private $staffLogRepo;

    public function __construct(LogRepo $staffLogRepo)
    {
        $this->staffLogRepo = $staffLogRepo;
    }
    /**
     * Handle VIEWING ACTION LOGS
     */
    public function index(Request $request)
    {
        $allLogs = $this->staffLogRepo->findAllWithPagination(
            $request->all(),
            ['*'],
            ['target', 'action_user', 'action_user.staff'],
            [
                'orderByDesc' => ['id'],
            ],
        );  
        $allLogs = $allLogs->appends(request()->except('page'));       
        $returnData = collect($allLogs)->only('data')->toArray();
        $paginationData = collect($allLogs)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.staff.log.index', [
            'logData' => $returnData,
            'pagination' => $paginationData,
            'allStaffs' => getFormattedStaffsList(),
            'allUsers' => getFormattedUsersListOfStaff(),
            'target' => UserEnum::TARGET_STAFF,
            'request' => $request->all(),
        ]);
    }
}