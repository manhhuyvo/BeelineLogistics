<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
use App\Enums\UserEnum;
use Illuminate\Support\Str;
use App\Repositories\User\LogRepo;

class LogController extends Controller
{   
    private $userLogRepo;

    public function __construct(LogRepo $userLogRepo)
    {
        $this->userLogRepo = $userLogRepo;
    }
    /**
     * Handle VIEWING ACTION LOGS
     */
    public function index(Request $request)
    {
        $allLogs = $this->userLogRepo->findAllWithPagination(
            $request->all(),
            ['*'],
            ['target', 'action_user', 'action_user.staff', 'action_user.customer', 'action_user.supplier'],
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

        return view('admin.user.log.index', [
            'logData' => $returnData,
            'pagination' => $paginationData,
            'allUsers' => getFormattedUsersList(),
            'request' => $request->all(),
        ]);
    }
}