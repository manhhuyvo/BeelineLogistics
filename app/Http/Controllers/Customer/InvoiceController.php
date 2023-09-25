<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\InvoiceEnum;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\GeneralEnum;
use App\Enums\ResponseStatusEnum;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    /** Displaying view for list of invoices */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Get list of all invoices with its relations
        $allInvoices = Invoice::with('customer', 'items')->where('customer_id', $user->customer->id);

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
                $allInvoices = $allInvoices->whereBetween('total_amount', [$data['total_amount_from'], $data['total_amount_to']]);
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

        return view('customer.invoice.list', [
            'invoices' => $returnData,
            'pagination' => $paginationData,
            'invoiceStatusColors' => InvoiceEnum::MAP_INVOICE_STATUS_COLORS,
            'invoiceStatuses' => InvoiceEnum::MAP_INVOICE_STATUSES,
            'paymentStatuses' => InvoiceEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => InvoiceEnum::MAP_PAYMENT_STATUS_COLORS,
            'bulkActions' => InvoiceEnum::MAP_BULK_ACTIONS,
            'exportRoute' => 'customer.invoice.export',
            'request' => $data,
        ]);
    }

    public function show(Request $request, Invoice $invoice)
    {
        // Get current logged-in user
        $user = Auth::user();

        // Get all needed lists
        $customer = collect($invoice->customer)->toArray();
        
        // If this invoice doesn't belong to the customer viewing, then return to previous page
        if ($user->customer->id != $customer['id']) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Configure the invoice items
        $invoiceItems = collect($invoice->items)->map(function($item) {
            // If this item is for fulfillment
            if ($item->fulfillment) {
                $item->item_type = InvoiceEnum::TARGET_FULFILLMENT;
                $item->target_id = $item->fulfillment_id;

                return collect($item)->toArray();
            }

            // If this item is for order
            if ($item->order) {
                $item->item_type = InvoiceEnum::TARGET_ORDER;
                $item->target_id = $item->order_id;

                return collect($item)->toArray();           
            }

            // If this item is for manual
            $item->item_type = InvoiceEnum::TARGET_MANUAL;

            return collect($item)->toArray();
        })->toArray();

        // Turn the invoice and its relationships into an array
        $invoice = collect($invoice)->toArray();

        // Return the view
        return view('customer.invoice.show', [
            'invoice' => $invoice,
            'customer' => $customer,
            'user' => $user,
            'invoiceStatusColors' => InvoiceEnum::MAP_INVOICE_STATUS_COLORS,
            'invoiceStatuses' => InvoiceEnum::MAP_INVOICE_STATUSES,
            'paymentStatuses' => InvoiceEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => InvoiceEnum::MAP_PAYMENT_STATUS_COLORS,
        ]);
    }

    /** Handle request for export action */
    public function export(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateExportRequest($request);                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Get the needed data from the request
        $data = collect($request->all())->only(['selected_rows', 'export_type'])->toArray();
        
        // Prepare data for export
        foreach ($data['selected_rows'] as $invoiceId) {
            
            // Get the invoice model
            $invoice = Invoice::find($invoiceId);

            // If the invoice is null, then add it to failed list
            if (!$invoice) {
                $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return redirect()->back()->with([
                    'response' => $responseData,
                    'request' => $request->all(),
                ]);
            }
        }

        $allInvoices = Invoice::with('customer', 'items')
                    ->whereIn('id', $data['selected_rows'])
                    ->get();

        // If we can't get the list of invoices, then just error out to the main page
        if ($allInvoices->isEmpty()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $exportData = collect($allInvoices)->map(function($invoice) {            
            // Manually assign an hcheck data for different columns
            $invoice['customer_name'] = "{$invoice['customer']['customer_id']} {$invoice['customer']['full_name']}";
            $invoice['status'] = InvoiceEnum::MAP_INVOICE_STATUSES[$invoice['status']] ?? 'Unknown';
            $invoice['payment_status'] = InvoiceEnum::MAP_PAYMENT_STATUSES[$invoice['payment_status']] ?? 'Unknown';
            $invoice['invoice_items'] = count($invoice['items']) ?? 0;
            $invoice['date_created'] = Carbon::parse($invoice['created_at'])->format('d/m/Y');
                    
            return collect($invoice)->only(InvoiceEnum::EXPORT_COLUMNS)->toArray();
        })->toArray();
        
        // Prepare the file for export
        $exportType = $data['export_type'] ?? GeneralEnum::EXPORT_TYPE_CSV;
        $fileName = "invoice_export.{$exportType}";
        $headers = array_merge(GeneralEnum::MAP_EXPORT_CONTENT_HEADERS[$exportType ?? GeneralEnum::EXPORT_TYPE_CSV], ['Content-Disposition' => "attachment; filename={$fileName}"]);
        // Columns
        $columns = collect(InvoiceEnum::EXPORT_COLUMNS)->map(function($column) {
            return ucwords(Str::replace('_', ' ', $column));
        })->toArray();

        // Export the file
        return response()->stream(function() use($columns, $exportData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($exportData as $row) {
                $fileRow = [
                    $row['id'] ?? '',
                    $row['customer_name'] ?? '',
                    $row['reference'] ?? '',
                    $row['total_amount'] ?? 0,
                    $row['outstanding_amount'] ?? 0,
                    $row['unit'] ?? '',
                    $row['due_date'] ?? '',
                    $row['status'] ?? '',
                    $row['payment_status'] ?? '',
                    $row['invoice_items'] ?? 0,
                    $row['date_created'] ?? '',
                ];
                fputcsv($file, $fileRow);
            }
            fclose($file);
        }, ResponseStatusEnum::CODE_SUCCESS, $headers);
    }

    /** Validate the request of invoice items */
    public function validateInvoiceItemsListRequest(Request $request)
    {        
        // Assign needed request keys to variables
        $invoiceItemArray = [
            'item_id' => $request->all()['item_id'] ?? [],
            'item_type' => $request->all()['item_type'] ?? [],
            'target_id' => $request->all()['target_id'] ?? [],
            'description' => $request->all()['description'] ?? [],
            'price' => $request->all()['price'] ?? [],
            'quantity' => $request->all()['quantity'] ?? [],
            'amount' => $request->all()['amount'] ?? [],
        ];

        // Restructure the invoice items into list
        $invoiceItems = [];
        foreach ($invoiceItemArray as $field => $values) {
            // If user didn't add any invoice items at all, then return false
            if (empty($values) && $field != 'item_id') {
                return false;
            }

            foreach ($values as $index => $row) {
                if (empty($row)) {
                    $row = $field == 'description' ? '' : 0;
                }
                $invoiceItems[$index][$field] = $row;
            }
        }

        return $invoiceItems;
    }

    /** Validate request for export actions */
    private function validateExportRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "export_type" => ["required", Rule::in(array_keys(GeneralEnum::MAP_EXPORT_TYPES))],
            "selected_rows" => ["required", "array"],
        ],
        [
            'selected_rows.required' => "Please provide a valid list of records for export",
        ]);

        return $validator;
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
}
