<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice\Item as InvoiceItem;
use App\Models\Fulfillment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\InvoiceEnum;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Enums\GeneralEnum;
use App\Enums\ResponseStatusEnum;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    /** Displaying view for list of invoices */
    public function index(Request $request)
    {
        // Get staff and customer list
        $staffsList = $this->formatStaffsList();
        $customersList = $this->formatCustomersList();

        // Get list of all invoices with its relations
        $allInvoices = Invoice::with('customer', 'items', 'staff');

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || Str::contains($key, 'date_') || Str::contains($key, 'amount_') || in_array($key, ['page', '_method', 'sort', 'direction'])) {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allInvoices = $allInvoices->where($key, 'like', "%$value%");
            }

            // Created between date range
            if (!empty($data['created_date_from']) && !empty($data['created_date_to'])) {
                $allInvoices = $data['created_date_from'] == $data['created_date_to']
                            ? $allInvoices->whereDate('created_at', $data['created_date_from'])
                            : $allInvoices->whereBetween('created_at', [$data['created_date_from'], $data['created_date_to']]);
            }

            // Due date between date range
            if (!empty($data['due_date_from']) && !empty($data['due_date_to'])) {
                $allInvoices = $data['due_date_from'] == $data['due_date_to']
                            ? $allInvoices->whereDate('due_date', $data['due_date_from'])
                            : $allInvoices->whereBetween('due_date', [$data['due_date_from'], $data['due_date_to']]);
            }

            // Total amount between range
            if (!empty($data['total_amount_from']) && !empty($data['total_amount_to'])) {
                $allInvoices = $allInvoices->whereBetween('total_amount_from', [$data['total_amount_from'], $data['total_amount_to']]);
            }
        }

        // Then add filter into the query
        $allInvoices = $allInvoices->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allInvoices = $allInvoices->appends(request()->except('page'));
        $returnData = $this->checkAndUpdateOverdueInvoice($allInvoices);
        $paginationData = collect($allInvoices)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.invoice.list', [
            'invoices' => $returnData,
            'pagination' => $paginationData,
            'customersList' => $customersList,
            'staffsList' => $staffsList,  
            'invoiceStatusColors' => InvoiceEnum::MAP_INVOICE_STATUS_COLORS,
            'invoiceStatuses' => InvoiceEnum::MAP_INVOICE_STATUSES,
            'paymentStatuses' => InvoiceEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => InvoiceEnum::MAP_PAYMENT_STATUS_COLORS,
            // 'bulkActions' => InvoiceEnum::MAP_BULK_ACTIONS,
            // 'exportRoute' => 'admin.fulfillment.export',
            'request' => $data,
        ]);
    }
    
    /** Handle request for creating new invoice */
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
        if (!empty($invoiceGenerationResponse['error']) || empty($invoiceGenerationResponse['success'])) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message($data['message'] ?? ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Eventually if there was no error returned during the whole process, then we return error message
        $responseData = viewResponseFormat()->success()->message($data['message'] ?? ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.invoice.list')->with(['response' => $responseData]);
    }
    

    /** Handle request for deleting invoice */
    public function destroy(Request $request, Invoice $invoice)
    {    
        // Perform deletion
        if (!$invoice->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.invoice.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.invoice.list')->with(['response' => $responseData]);
    }

    /** Handle request for generating invoice from Fulfillment list or Order list */
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
            // Get current logged-in user
            $user = Auth::user();
            // Start transaction
            DB::beginTransaction();

            // Get the invoice reference first
            $invoiceReference = $recordsList['reference'] ?? '';
            $recordsList = collect($recordsList)->except(['reference'])->toArray();

            // Loop through the list
            foreach ($recordsList as $customerId => $invoiceItems) {
                // Create Invoice
                $invoice = new Invoice([
                    'customer_id' => $customerId,
                    'staff_id' => $user->id ?? 0,
                    'reference' => htmlspecialchars($invoiceReference ?? ''),
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
                $invoice->outstanding_amount = $invoiceTotalAmount;           
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
            'reference' => ["sometimes", "nullable", "nullable", "between:0, 255"],
        ];
        $customMessage = [
            'create_invoice_from' => 'Please provide a valid source of invoice for generation',
            'reference' => 'Please provide a valid format for invoice reference',
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
                'invoice_reference',
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

                    // If no error occurred, then assign the reference for invoice
                    $invoiceDetails['reference'] = $data['invoice_reference'] ?? '';

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
            if ($type == InvoiceEnum::TARGET_ORDER) {
                return [
                    'error' => $type,
                    'message' => ResponseMessageEnum::FAILED_ADD_NEW_RECORD,
                ];
            }
        }

        // Otherwise if this invoice is created manually, then we pick other fields
        return [];
    }

    /** Update any overdue invoice in database status */
    private function checkAndUpdateOverdueInvoice($invoices)
    {
        // If for some reasons the invoice list is empty, then we just return it empty as it is
        if (empty($invoices)) {
            return [];
        }
        
        // Final data holder
        $finalData = [];
        foreach ($invoices as $invoice) {
            $dueDate = Carbon::parse($invoice['due_date']);
            
            // Check if this due date is still not yet, then we don't do anything, add it back to the lsit
            if (!$dueDate->isPast()) {
                $finalData['data'][] = collect($invoice)->toArray();
                continue;
            }

            // Otherwise if it has passed, then we update status as Overdue in the database
            $invoice->status = InvoiceEnum::STATUS_OVERDUE;
            $invoice->save();

            // After update, then we add it bnack to the list
            $finalData['data'][] = collect($invoice)->toArray();
        }

        return $finalData;
    }
    
    /** Format the array for customers list */
    private function formatCustomersList(string $listType = '')
    {
        $filterStatuses = (!empty($listType) && $listType  == "all") ? Customer::CUSTOMER_STATUSES : [
            Customer::STATUS_ACTIVE,
            Customer::STATUS_PENDING,
        ];

        $allCustomers = Customer::whereIn('status', $filterStatuses)
                    ->select('id', 'full_name', 'customer_id')
                    ->get();
        
        $data = [];
        foreach ($allCustomers as $customer) {
            $data[$customer['id']] = "{$customer['full_name']} ({$customer['customer_id']})";
        }

        return $data;
    }

    /** Format the array for staffs list */
    private function formatStaffsList(string $listType = '')
    {
        $filterStatuses = (!empty($listType) && $listType  == "all") ? Staff::STAFF_STATUSES : [
            Staff::STATUS_CURRENT,
            StafF::STATUS_TEMPORARY_OFF,
        ];

        $allStaffs = Staff::whereIn('status', $filterStatuses)
                    ->select('id', 'full_name', 'position')
                    ->get();
        
        $data = [];
        foreach ($allStaffs as $staff) {
            $position = strtoupper(Staff::MAP_POSITIONS[$staff['position']]) ?? "Position Not Found";
            $data[$staff['id']] = "{$staff['full_name']} ({$position})";
        }

        return $data;
    }
}
