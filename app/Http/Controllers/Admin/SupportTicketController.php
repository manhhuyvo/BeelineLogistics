<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Models
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use App\Models\User;
use App\Models\Fulfillment;
use App\Models\Order;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\ResponseMessageEnum;
use App\Enums\GeneralEnum;
use App\Models\Customer;
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
        if (!$user || !$user->isStaff() || empty($user->staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Retrieve list of all first
        $allTickets = SupportTicket::with(['userCreated', 'userSolved', 'comments', 'customer']);

        // If this is not admin, then only show the fulfillments that this staff manage
        if (!$user->staff->isAdmin()) {
            $allTickets = $allTickets->whereHas('customer', function($query) use ($user) {
                $query->where('staff_id', $user->staff->id);
            });
        }
        
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

    public function create(Request $request)
    {        
        $user = Auth::user();

        $allCustomers = getFormattedCustomersList();

        // Return the view
        return view('admin.ticket.create', [
            'user' => $user,
            'supportTicketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'allCustomers' => $allCustomers,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rawData = collect($request->all())->except(['attachments'])->toArray();

        // Validate the request coming
        $validation = $this->validateRequest($rawData);                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        // If this customer doesn't belong to a non-admin staff
        $customer = Customer::find($rawData['customer_id']);
        if (!$user->staff->isAdmin() && $customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }
        
        DB::beginTransaction();
        // Check if the file has been uploaded, and save that file to public
        if ($request->hasFile('attachments')) {
            // Prepare some values to save to database and store image
            $today = Carbon::now()->format('d_m_Y');
            $randomString = generateRandomString(8);
            $fileName = "ticket_attachment_{$today}_{$randomString}";
            $path = $this->UploadFile($request->file('attachments'), null, 'public', $fileName);
            // The file which has been uploaded
            $uploadedFile = $request->file('attachments');

            // Save the file to public/fulfillment_payment_receipts folder
            $uploadedFile->move(public_path() . '/ticket_attachments', $path);
        }

        // Create new support ticket
        $newTicket = new SupportTicket([
            'customer_id' => $rawData['customer_id'],
            'created_user_id' => $user->id,
            'solved_user_id' => 0,
            'title' => htmlspecialchars($rawData['title'] ?? ''),
            'content' => $rawData['content'] ?? '',
            'status' => SupportTicketEnum::STATUS_ACTIVE,
            'note' => '',
            'attachments' => $path ?? '',
        ]);
        
        // If create not successfull
        if (!$newTicket->save()) {            
            // Rollback
            DB::rollBack();
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        // Validate and format the items list provided
        $itemsList = $this->validateItemsList($rawData);
        // If the items list is empty, then we don't do anything else, just end it here
        if (empty($itemsList)) {
            DB::commit();
            $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();
    
            return redirect()->back()->with(['response' => $responseData]);
        }

        // Loop through each type of items and create relevant pivot records for them
        foreach ($itemsList as $type => $ids) {
            if (!in_array($type, [SupportTicketEnum::TARGET_FULFILLMENT, SupportTicketEnum::TARGET_ORDER])) {
                continue;
            }

            if ($type == SupportTicketEnum::TARGET_ORDER) {
                $newTicket->orders()->attach($ids);
                continue;
            }

            $newTicket->fulfillments()->attach($ids);
        }
        
        // At the end, even if we have attached the above events or not, we just commit changes and return success message
        DB::commit();
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    public function show(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->staff->isAdmin() && $ticket->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

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
        
        if (!$user->staff->isAdmin() && $ticket->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
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
        
        if (!$user->staff->isAdmin() && $ticket->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
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
        
        if (!$user->staff->isAdmin() && $ticket->customer->staff_id != $user->staff->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
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

    /** Validate the item list of the request */
    private function validateItemsList(array $rawData)
    {
        $itemTypes = $rawData['item_type'] ?? [];
        $itemIds = $rawData['item_id'] ?? [];

        $customerId = $rawData['customer_id'];

        $items = [];
        foreach ($itemTypes as $index => $type) {
            if (empty($type) || empty($itemIds[$index])) {
                continue;
            }

            $itemId = $itemIds[$index];
            // Check if this item ID is actually exists and belong to the selected customer
            if ($type == GeneralEnum::TARGET_FULFILLMENT) {
                $item = Fulfillment::find($itemId);
                if (!$item) {
                    continue;
                }

                if ($item->customer_id != $customerId) {
                    continue;
                }
            }

            // Check if this item ID is actually exists and belong to the selected customer
            if ($type == GeneralEnum::TARGET_ORDER) {
                $item = Order::find($itemId);
                if (!$item) {
                    continue;
                }

                if ($item->customer_id != $customerId) {
                    continue;
                }
            }

            $items[$type][] = $itemIds[$index];
        }

        return $items;
    }

    /** Validate normal fields of the request */
    private function validateRequest(array $rawData)
    {
        $validator = Validator::make($rawData, [
            "customer_id" => ["required", "exists:App\Models\Customer,id"],
            "title" => ["required"],
            "content" => ["required"],
        ]);

        return $validator;
    }
}
