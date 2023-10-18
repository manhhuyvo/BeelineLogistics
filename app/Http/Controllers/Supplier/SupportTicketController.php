<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
// Models
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use App\Models\Fulfillment;
use App\Models\Order;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\ResponseMessageEnum;
use App\Enums\GeneralEnum;
// Helpers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload;
use Illuminate\Support\Str;
use Exception;

class SupportTicketController extends Controller
{
    use Upload;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Retrieve list of all first
        $allTickets = SupportTicket::whereHas('fulfillments', function ($query) use ($user) {
                            $query->where('supplier_id', $user->supplier->id);
                        })
                        ->with(['userCreated', 'userSolved', 'comments', 'customer']);
        
        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            // All other fields
            foreach($data as $key => $value) {
                if (!in_array($key, SupportTicketEnum::SUPPLIER_FILTERABLE_COLUMNS) || empty($value)) {
                    continue;
                }

                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allTickets = $allTickets->where($key, 'like', "%$value%");
            }

            // Solved between date range
            if (!empty($data['solved_from']) && !empty($data['solved_to'])) {
                $allTickets = $data['solved_from'] == $data['solved_to']
                                ? $allTickets->whereDate('solved_date', $data['date_from'])
                                : $allTickets->whereBetween('solved_date', [
                                    Carbon::parse($data['solved_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($data['solved_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }

            // Created between date range
            if (!empty($data['date_from']) && !empty($data['date_to'])) {
                $allTickets = $data['date_from'] == $data['date_to']
                                ? $allTickets->whereDate('created_at', $data['date_from'])
                                : $allTickets->whereBetween('created_at', [
                                    Carbon::parse($data['date_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($data['date_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }
        }
        $allTickets = $allTickets->orderBy('status', 'asc');

        // Then add filter into the query
        $allTickets = $allTickets->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allTickets = $allTickets->appends(request()->except('page'));
        $returnData = collect($allTickets)->only('data')->toArray();
        
        // Get model for each of product in the fulfillment
        $paginationData = collect($allTickets)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        $customersList = getFormattedCustomersListForSupplier();

        return view('supplier.ticket.list', [
            'customersList' => $customersList,
            'tickets' => $returnData,
            'pagination' => $paginationData,   
            'ticketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'ticketStatusColors' => SupportTicketEnum::MAP_STATUS_COLORS,
            'exportRoute' => 'supplier.ticket.export',
            'request' => $data,
        ]);
    }
}
