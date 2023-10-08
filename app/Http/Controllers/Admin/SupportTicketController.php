<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Models
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use App\Models\User;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\ResponseMessageEnum;
// Helpers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    use Upload;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Retrieve list of all first
        $allTickets = SupportTicket::with(['userCreated', 'userSolved', 'comments']);
        
        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            // All other fields
            foreach($data as $key => $value) {
                if (!in_array($key, SupportTicketEnum::STAFF_FILTERABLE_COLUMNS) || empty($value)) {
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

        $formatted = collect($allTickets->items())
                ->map(function(SupportTicket $ticket) {
                    $userCreatedOwner = $ticket->userCreated->getUserOwner();
                    $userSolvedOwner =  $ticket->userSolved ? $ticket->userSolved->getUserOwner() : null;
                    $ticket = $ticket->toArray();

                    if (!empty($ticket['user_created'])) {
                        $ticket['user_created']['owner'] = $userCreatedOwner->toArray();
                    }
                    
                    if (!empty($ticket['user_solved'])) {
                        $ticket['user_solved']['owner'] = $userSolvedOwner->toArray();
                    }

                    return $ticket;
                })
                ->toArray();

        $returnData['data'] = $formatted;
        
        // Get model for each of product in the fulfillment
        $paginationData = collect($allTickets)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        $allStaffUsers = getFormattedUsersListOfStaff();
        $allCustomers = getFormattedCustomersList();

        return view('admin.ticket.list', [
            'tickets' => $returnData,
            'pagination' => $paginationData,   
            'ticketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'ticketStatusColors' => SupportTicketEnum::MAP_STATUS_COLORS,
            'staffUsers' => $allStaffUsers,
            'allCustomers' => $allCustomers,
            'exportRoute' => 'admin.ticket.export',
            'request' => $data,
        ]);
    }

    public function show(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        $customer = $ticket->customer;
        $userCreatedOwner = $ticket->userCreated ? $ticket->userCreated->getUserOwner() : null;
        $userSolvedOwner = $ticket->userSolved ? $ticket->userSolved->getUserOwner() : null;
        $fulfillments = $ticket->fulfillments;
        $orders = $ticket->orders;
        $comments = $ticket->comments ?? [];
        $comments = collect($comments)->map(function(SupportTicketComment $comment) {
            $userComment = $comment->user;
            $ownerComment = collect($userComment->getUserOwner())->toArray();
            $comment = collect($comment)->toArray();
            $comment['owner'] = $ownerComment;

            return $comment;
        })
        ->toArray();

        $ticket = $ticket->toArray();

        if (!empty($ticket['user_created'])) {
            $ticket['user_created']['owner'] = $userCreatedOwner->toArray();
        }
        
        if (!empty($ticket['user_solved'])) {
            $ticket['user_solved']['owner'] = $userSolvedOwner->toArray();
        }

        // Return the view
        return view('admin.ticket.show', [
            'user' => $user,
            'ticket' => $ticket,
            'comments' => collect($comments)->toArray(),
            'supportTicketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'supportTicketStatusColors' => SupportTicketEnum::MAP_STATUS_COLORS,
        ]);
    }

    public function solve(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        if ($user->target == User::TARGET_CUSTOMER) {            
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.dashboard')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Update status and user solved details
        $ticket->status = SupportTicketEnum::STATUS_SOLVED;
        $ticket->solved_user_id = $user->id;
        $ticket->solved_date = Carbon::now()->format('Y-m-d H:i:s');

        // If save unsuccessfully
        if (!$ticket->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    public function active(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        if ($user->target == User::TARGET_CUSTOMER) {            
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.dashboard')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Update status and user solved details
        $ticket->status = SupportTicketEnum::STATUS_ACTIVE;
        $ticket->solved_user_id = 0;
        $ticket->solved_date = null;

        // If save unsuccessfully
        if (!$ticket->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    public function delete(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        if ($user->target == User::TARGET_CUSTOMER) {            
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->route('admin.dashboard')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Update status and user solved details
        $ticket->status = SupportTicketEnum::STATUS_DELETED;

        // If save unsuccessfully
        if (!$ticket->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }
}
