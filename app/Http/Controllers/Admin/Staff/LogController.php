<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
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
        $allLogs = $this->staffLogRepo->getAllWithPagination($request->all());     
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
        ]);
    }
}