<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice\Item as InvoiceItem;
use App\Models\Fulfillment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\InvoiceEnum;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Enums\GeneralEnum;
use App\Enums\ResponseStatusEnum;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        // Although this case would probably never happen, we should just check if it's empty for some reasons, then we throw an error
        if (empty($data) || empty($data['success']) || empty($data['data'])) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // If the $data after sanitizing is empty or has an error occurred, then we return error
        if (!empty($data['error'])) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message($data['message'] ?? ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // If all the data was generated successfully, then we start creating records into the database
        $invoiceGenerationResponse = $this->handleAutoGeneration($data['success'], $data['data']);        

        // If some errors occurred during the database actions, then we display that error to the FE
        if (!empty($data['error']) || empty($data['success'])) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message($data['message'] ?? ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Eventually if there was no error returned during the whole process, then we return error message
        $responseData = viewResponseFormat()->success()->message($data['message'] ?? ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.fulfillment.list')->with(['response' => $responseData]);
    }

    private function handleAutoGeneration(string $type, array $recordsList)
    {
        // If some random errors occurred from somewhere, we just prevent it breaking the page by returning UNKNOWN error
        if (empty($type) || !in_array($type, InvoiceEnum::AUTO_TARGETS) || empty($recordsList)) {
            return [
                'error' => $type,
                'message' => ResponseMessageEnum::UNKNOWN_ERROR,
            ];
        }

        try {
            // Start transaction
            DB::beginTransaction();

            foreach ($recordsList as $customerId => $invoiceItems) {
                // Create Invoice
                $invoice = new Invoice([
                    'customer_id' => $customerId,
                    'total_amount' => 0, // Set the amount is 0, we can update it later
                    'outstanding_amount' => 0, // Set the amount is 0, we can update it later
                    'unit' => CurrencyAndCountryEnum::CURRENCY_USD, // Set the unit is USD for safety so in case something breaks, we always have unit set as USD, we can update it later
                    'due_date' => Carbon::now()->addDays(InvoiceEnum::DEFAULT_DUE_DATE)->toDateTimeString(), // Default due day is 10 days from the date created
                    'status' => InvoiceEnum::STATUS_PENDING, // Default when creating invoice is as PENDING. Accountant needs to approve it
                    'payment_status' => InvoiceEnum::STATUS_UNPAID, // Default payment status is as UNPAID
                    'note' => '', // Default note is as empty string
                ]);

                // If some errors occurred during creating the invoice, then we rollback and return error
                if(!$invoice->save()) {
                    // Rollback
                    DB::rollBack();

                    // Return error
                    return [
                        'error' => $type,
                        'message' => ResponseMessageEnum::FAILED_ADD_NEW_RECORD,
                    ];
                }

                /** If the invoice was created successfully, we loop through the list of invoice items and add them to the invoice */
                $invoiceTotalAmount = 0;
                foreach ($invoiceItems as $item) {
                    // Assign this new invoice item into the invoice that we jsut created
                    $item['invoice_id'] = $invoice->id ?? '';
                    // Add the amount of every invoice item into the total invoice amount so we can update it later
                    $invoiceTotalAmount += $item['amount'] ?? 0;
                    // Assign the unit of the latest invoice item into the invoice unit, normally all of the should be the same unit (currency)
                    $invoice->unit = $item['unit'] ?? $invoice->unit;

                    // Create this new invoice item
                    $newInvoiceItem = new InvoiceItem($item);

                    // if some error occurred during saving item to database, we rollback and display error
                    if(!$newInvoiceItem->save()) {
                        // Rollback
                        DB::rollBack();

                        // Return error
                        return [
                            'error' => $type,
                            'message' => ResponseMessageEnum::FAILED_ADD_NEW_RECORD,
                        ];
                    }
                }

                // If there was no errors occurred during looping and creating invoice items, then we update details for $invoice
                $invoice->total_amount = $invoiceTotalAmount;           
                // If some errors occurred during creating the invoice, then we rollback and return error
                if(!$invoice->save()) {
                    // Rollback
                    DB::rollBack();

                    // Return error
                    return [
                        'error' => $type,
                        'message' => ResponseMessageEnum::FAILED_ADD_NEW_RECORD,
                    ];
                }
            }
        } catch (Exception $e) {
            // If an exception was cautch, then we rollBack and display error
            // Rollback
            DB::rollBack();

            // Return error
            return [
                'error' => $type,
                'message' => ResponseMessageEnum::UNKNOWN_ERROR,
            ];
        }

        // If we have came to this part, that mean we should commit the changes and display success message
        DB::commit();

        return [
            'success' => $type,
            'message' => ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD,
        ];
    }

    /** Validate the request for creating or updating */
    public function validateRequest(Request $request)
    {
        // Get the request as array
        $data = $request->all();

        // Initiate the rule and custom message for validation
        $rules = [
            'create_invoice_from' => ["required", Rule::in(array_keys(InvoiceEnum::MAP_TARGETS))],
        ];
        $customMessage = [
            'create_invoice_from' => 'Please provided a valid source of invoice for generation',
        ];

        // If we are creating invoice Order or Fulfillment, then we validate the key 'selected_rows'
        if (in_array($data['create_invoice_from'], InvoiceEnum::AUTO_TARGETS)) {
            // Assign validation for selected_rows key
            $rules['selected_rows'] = ["required", "array"];
            $rules['selected_rows.*'] = $data['create_invoice_from'] == InvoiceEnum::TARGET_FULFILLMENT 
                                    ? ["exists:App\Models\Fulfillment,id"]
                                    : ["exists:App\Models\Order,id"];

            // Set custom message for selected_rows key
            $customMessage['selected_rows'] = "Please provide a valid list of records for generating invoice";
            $customMessage['selected_rows.*'] = "Please provide a valid list of records for generating invoice";
        }

        // Otherwise if we are creating from manual process, then we have to validate a bunch of other things

        //  Make the validator
        $validator = Validator::make($data, $rules, $customMessage);

        return $validator;
    }

    /** Format the request and only take out the needed keys */
    public function formatRequestData(Request $request)
    {
        $data = $request->all();

        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }

        // Invoice generation type
        $type = $data['create_invoice_from'] ?? InvoiceEnum::TARGET_MANUAL;

        // If we are creating invoice Order or Fulfillment, then we only pick two keys
        if (in_array($type, InvoiceEnum::AUTO_TARGETS)) {
            // Get the needed keys
            $data = collect($data)->only([
                'create_invoice_from',
                'selected_rows',
            ])->toArray();

            // If this is the invoice created from FULFILLMENT
            if ($type == InvoiceEnum::TARGET_FULFILLMENT) {
                try {
                    // If the list of records belong to different customers, then we return error
                    $customerCheck = Fulfillment::whereIn('id', $data['selected_rows'])
                                    ->groupBy('customer_id')
                                    ->get();
                    
                    // If more than one row found, that means the provided records belong to different customers, then return error
                    if (count($customerCheck) != 1) {
                        return [
                            'error' => $type,
                            'message' => ResponseMessageEnum::MANY_CUSTOMERS_ITEMS,
                        ];
                    }
                
                    // Placeholder for invoice's details
                    $invoiceDetails = [];

                    foreach ($data['selected_rows'] as $recordId) {
                        // If the invoice is creating from fulfillments list
                        $fulfillment = Fulfillment::find($recordId);
                        
                        // If for some reasons we cannot find the customer, just end it and throw errors back to FE
                        if (!$fulfillment) {
                            return [
                                'error' => $type,
                                'message' => ResponseMessageEnum::FAILED_FULFILLMENT_RETRIEVE,
                            ];
                        }

                        // Get fulfillments amount for invoice
                        $fulfillmentPostage = (float) $fulfillment->postage ?? 0;
                        $fulfillmentLabour = (float) $fulfillment->total_labour_amount ?? 0;
        
                        // PREPARING DESCRIPTION FOR EACH INVOICE ITEM
                        // Fulfillment's receiver details
                        $fulfillmentName = $fulfillment->name ?? "Not Provided";
                        $fulfillmentPhone = $fulfillment->phone ?? "Not Provided";
                        $fulfillmentAddress2 = !empty($fulfillment->address2) ? " {$fulfillment->address2}," : "";
                        $fulfillmentCountry = CurrencyAndCountryEnum::MAP_COUNTRIES[$fulfillment->country ?? ''] ?? '';
                        $fulfillmentAddress = "{$fulfillment->address},{$fulfillmentAddress2} {$fulfillment->suburb} {$fulfillment->state} {$fulfillment->postcode} {$fulfillmentCountry}";
                        $fulfillmentProductDetails = !empty($fulfillment->total_product_amount) ? "{$fulfillment->total_product_amount} {$fulfillment->product_unit}" : "Not Provided";
                        $fulfillmentPostageDetails = !empty($fulfillment->postage) ? "{$fulfillment->postage} {$fulfillment->postage_unit}" : "Not Provided";
                        $fulfillmentLabourDetails = !empty($fulfillment->total_labour_amount) ? "{$fulfillment->total_labour_amount} {$fulfillment->labour_unit}" : "Not Provided";
        
                        // Format the data for invoice and invoice items
                        $invoiceDetails[$fulfillment->customer_id][] = [
                            'fulfillment_id' => $recordId,
                            // Total amount of invoice created from fulfillment is the sum of postage and labour cost
                            'amount' => $fulfillmentPostage + $fulfillmentLabour,
                            // Invoice's unit is prioritily the unit of labour, otherwise it's the postage unit or even worst, we default it as USD
                            'unit' => $fulfillment->labour_unit ?? ($fulfillment->postage_unit ?? CurrencyAndCountryEnum::CURRENCY_USD),
                            // Invoice item description is the fulfillment receiver details
                            'description' => InvoiceEnum::MAP_DESCRIPTION_TARGET[$type] . " Details of Fulfillment:</br>- Fulfillment ID: #{$recordId}</br>- Receiver Name: {$fulfillmentName}</br>- Receiver Phone: {$fulfillmentPhone}</br>- Receiver Address: {$fulfillmentAddress}</br>- Product Amount: {$fulfillmentProductDetails}</br>- Labour Amount: {$fulfillmentLabourDetails}</br>- Postage: {$fulfillmentPostageDetails}",
                            // Just default the note as empty string
                            'note' => '',
                        ];
                    }

                    // Return the formatted as the list of invoice items and its customer id
                    return [
                        'success' => $type,
                        'data' => $invoiceDetails,
                    ];
                } catch (Exception $e) {
                    // If an exception was thrown, then we return UNKNOWN error to the FE
                    return [
                        'error' => $type,
                        'message' => ResponseMessageEnum::UNKNOWN_ERROR,
                    ];
                }
            }

            // TODO: INVOICE CREATED FOR ORDER
        }

        // Otherwise if this invoice is created manually, then we pick other fields
        return [];
    }
}
