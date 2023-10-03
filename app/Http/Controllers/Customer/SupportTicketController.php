<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
// Models
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\ResponseMessageEnum;
// Helpers
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload;
use Exception;

class SupportTicketController extends Controller
{
    use Upload;

    public function index(Request $request)
    {

    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // Return the view
        return view('customer.ticket.create', [
            'user' => $user,
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

    public function update(Request $request, SupportTicket $ticket)
    {

    }

    /** Validate the item list of the request */
    private function validateItemsList(array $itemsList)
    {
        $itemTypes = $itemsList['item_type'] ?? [];
        $itemIds = $itemsList['item_id'] ?? [];

        $items = [];
        foreach ($itemTypes as $index => $type) {
            if (empty($type) || empty($itemIds[$index])) {
                continue;
            }

            $items[$type][] = $itemIds[$index];
        }

        return $items;
    }

    /** Validate normal fields of the request */
    private function validateRequest(array $rawData)
    {
        $validator = Validator::make($rawData, [
            "title" => ["required"],
            "content" => ["required"],
        ]);

        return $validator;
    }
}
