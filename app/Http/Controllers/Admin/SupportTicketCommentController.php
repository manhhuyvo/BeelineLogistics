<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Models
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\ResponseMessageEnum;
// Helpers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload;
use Illuminate\Support\Facades\URL;
use Exception;

class SupportTicketCommentController extends Controller
{
    use Upload;

    public function store(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();
        $rawData = collect($request->all())->except(['attachment'])->toArray();

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
        if ($request->hasFile('attachment')) {
            // Prepare some values to save to database and store image
            $today = Carbon::now()->format('d_m_Y');
            $randomString = generateRandomString(8);
            $fileName = "ticket_comment_attachment_{$today}_{$randomString}";
            $path = $this->UploadFile($request->file('attachment'), null, 'public', $fileName);
            // The file which has been uploaded
            $uploadedFile = $request->file('attachment');

            // Save the file to public/ticket_comments_attachments folder
            $uploadedFile->move(public_path() . '/ticket_comments_attachments', $path);
        }

        // Create new comment
        $newComment = new SupportTicketComment([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'content' => $rawData['content'] ?? '',
            'attachment' => $path ?? '',
        ]);

        // If create not successfull
        if (!$newComment->save()) {            
            // Rollback
            DB::rollBack();
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        DB::commit();
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    /** Validate normal fields of the request */
    private function validateRequest(array $rawData)
    {
        $validator = Validator::make($rawData, [
            "content" => ["required"],
        ]);

        return $validator;
    }
}
