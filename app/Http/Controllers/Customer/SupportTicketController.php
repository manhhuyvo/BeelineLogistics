<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
// Models
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
// Enums
use App\Enums\SupportTicketEnum;
// Helpers
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {

    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $fulfillmentsList = getFormattedFulfillmentsList();
        $ordersList = getFormattedOrdersList();

        // Return the view
        return view('customer.ticket.create', [
            'user' => $user,
            'fulfillmentsList' => $fulfillmentsList,
            'ordersList' => $ordersList,
            'supportTicketStatuses' => SupportTicketEnum::MAP_STATUSES,
        ]);
    }

    public function edit(Request $request, SupportTicket $ticket)
    {

    }

    public function show(Request $request, SupportTicket $ticket)
    {

    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function update(Request $request, SupportTicket $ticket)
    {

    }
}
