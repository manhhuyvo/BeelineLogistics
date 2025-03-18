<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
use App\Enums\CustomerEnum;
use App\Enums\UserEnum;
use Illuminate\Support\Str;
use App\Repositories\Customer\LogRepo;

class LogController extends Controller
{   
    private $customerLogRepo;

    public function __construct(LogRepo $customerLogRepo)
    {
        $this->customerLogRepo = $customerLogRepo;
    }
    /**
     * Handle VIEWING ACTION LOGS
     */
    public function index(Request $request)
    {
        $allLogs = $this->customerLogRepo->findAllWithPagination(
            $request->all(),
            ['*'],
            ['target', 'action_user.staff', 'action_user.customer'],
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

        return view('admin.customer.log.index', [
            'logData' => $returnData,
            'pagination' => $paginationData,
            'allCustomers' => getFormattedCustomersList(),
            'allUsers' => getFormattedUsersList([UserEnum::TARGET_SUPPLIER]),
            'request' => $request->all(),
        ]);
    }
}