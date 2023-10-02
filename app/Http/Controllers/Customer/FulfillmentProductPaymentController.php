<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fulfillment;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Enums\ProductPaymentEnum;
use App\Enums\ResponseMessageEnum;
use Carbon\Carbon;
use App\Traits\Upload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FulfillmentProductPaymentController extends Controller
{
    use Upload;
    
    public function addPayment(Request $request, Fulfillment $fulfillment)
    {
        $user = Auth::user();

        // Check if this fulfillment belongs to this user
        if ($fulfillment && $fulfillment->customer_id != $user->customer->id) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }
        
        // Check if the file has been uploaded
        if ($request->hasFile('payment_receipt')) {
            // Prepare some values to save to database and store image
            $today = Carbon::now()->format('d_m_Y');
            $randomString = generateRandomString(8);
            $fileName = "fulfillment_{$fulfillment->id}_payment_receipt_{$today}_{$randomString}";
            $path = $this->UploadFile($request->file('payment_receipt'), null, 'public', $fileName);
            // The file which has been uploaded
            $uploadedFile = $request->file('payment_receipt');

            // Save the file to public/fulfillment_payment_receipts folder
            $uploadedFile->move(public_path() . '/fulfillment_payment_receipts', $path);
        }

        $rawData = collect($request->all())->except(['payment_receipt'])->toArray();

        // Create the record for payment
        // Validate the request coming
        $validation = $this->validateAddPaymentRequest($rawData);
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        // Format the request data
        $data = $this->formatAddPaymentRequestData($rawData);
        // Add other fields for saving to database
        $data['payment_receipt'] = $path ?? '';
        $data['status'] = ProductPaymentEnum::STATUS_PENDING;
        $data['user_id'] = $user->id;
        $data['fulfillment_id'] = $fulfillment->id;

        // Creating new record of fulfillment product payment
        $newRecord = new FulfillmentProductPayment($data);
        if (!$newRecord->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    private function validateAddPaymentRequest(array $request)
    {
        $validator = Validator::make($request, [
            // Customer Details
            "fulfillment_id" => ["required", "exists:App\Models\Fulfillment,id"],
            "user_id" => ["required", "exists:App\Models\User,id"],
            'description' => ["sometimes", "nullable", "nullable", "between:0, 255"],
            'amount' => ['required', 'numeric', 'between:0,99999.99'],
            'payment_method' => ['required', Rule::in(array_keys(ProductPaymentEnum::MAP_PAYMENT_METHODS))],
            'status' => ['required', Rule::in(array_keys(ProductPaymentEnum::MAP_STATUSES))],
            'payment_date' => ['required', 'date'],
        ]);

        return $validator;
    }

    private function formatAddPaymentRequestData(array $request)
    {
        $data = $request;

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }

        return collect($data)->only([
            'fulfillment_id',
            'user_id',
            'description',
            'amount',
            'payment_method',
            'status',
            'payment_date',
        ])->toArray();
    }
}
