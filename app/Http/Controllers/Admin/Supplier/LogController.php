<?php

namespace App\Http\Controllers\Admin\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ResponseMessageEnum;
use App\Enums\UserEnum;
use Illuminate\Support\Str;
use App\Repositories\Supplier\LogRepo;

class LogController extends Controller
{
    private $supplierLogRepo;

    public function __construct(LogRepo $supplierLogRepo)
    {
        $this->supplierLogRepo = $supplierLogRepo;
    }
    /**
     * Handle VIEWING ACTION LOGS
     */
    public function index(Request $request)
    {
        $allLogs = $this->supplierLogRepo->findAllWithPagination(
            $request->all(),
            ['*'],
            ['target', 'action_user', 'action_user.staff', 'action_user.supplier'],
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

        return view('admin.supplier.log.index', [
            'logData' => $returnData,
            'pagination' => $paginationData,
            'allSuppliers' => getFormattedSuppliersList(),
            'allUsers' => getFormattedUsersList([UserEnum::TARGET_CUSTOMER]),
            'request' => $request->all(),
        ]);
    }
}
