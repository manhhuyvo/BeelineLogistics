<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
// Models
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Models\CountryServiceConfiguration;
use App\Models\Supplier;
// Enums
use App\Enums\FulfillmentEnum;
use App\Enums\ProductPaymentEnum;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\CustomerMetaEnum;
use App\Enums\GeneralEnum;
use App\Enums\ResponseStatusEnum;
use App\Enums\SupportTicketEnum;
use App\Enums\SupplierMetaEnum;
use App\Enums\SupplierEnum;
// Helpers
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class FulfillmentController extends Controller
{
    /** Display the page for list of all customers */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Retrieve list of all first
        $allFulfillments = Fulfillment::where('supplier_id', $user->supplier->id)
                        ->with(['supportTickets', 'customer']);

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            // All other fields
            foreach($data as $key => $value) {
                if (!in_array($key, FulfillmentEnum::SUPPLIER_FILTERABLE_COLUMNS) || empty($value)) {
                    continue;
                }

                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allFulfillments = $allFulfillments->where($key, 'like', "%$value%");
            }

            // Shipping Status
            if (!empty($data['ticket_status'])) {
                $allFulfillments = $allFulfillments->whereHas('supportTickets', function($query) use ($data, $user) {
                    $query->where('status', '=', $data['ticket_status'])
                        ->whereHas('fulfillments', function($subQuery) use ($user) {
                            $subQuery->where('supplier_id', $user->supplier->id);
                        });
                });
            }

            // Created between date range
            if (!empty($data['date_from']) && !empty($data['date_to'])) {
                $allFulfillments = $data['date_from'] == $data['date_to']
                                ? $allFulfillments->whereDate('created_at', $data['date_from'])
                                : $allFulfillments->whereBetween('created_at', [
                                    Carbon::parse($data['date_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($data['date_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }
        }

        // Then add filter into the query
        $allFulfillments = $allFulfillments->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allFulfillments = $allFulfillments->appends(request()->except('page'));     
        $returnData = collect($allFulfillments)->only('data')->toArray();
        // Getting the products models
        $returnData['data'] = collect($returnData['data'])
                ->map(function ($fulfillment) use ($user){
                    $fulfillment['product_configs'] = unserialize($fulfillment['product_configs']);

                    // Get product's model and its group model
                    $productConfigsPlaceholder = [];
                    foreach ($fulfillment['product_configs'] as $product) {
                        // Find the product model, product group and turn it to array
                        $productModel = Product::find($product['product_id']) ?? [];
                        $productGroup = !empty($productModel) ? collect($productModel->productGroup)->toArray() : [];
                        $product['model'] = array_merge(collect($productModel)->toArray(), ['product_group' => $productGroup]);

                        // Assign this formatted product to the list
                        $productConfigsPlaceholder[] = $product;
                    }

                    $fulfillment['product_configs'] = $productConfigsPlaceholder;

                    // Only pick the support ticket with active status
                    $fulfillment['support_tickets'] = collect($fulfillment['support_tickets'])
                                                    ->filter(function($ticket) use ($user) {
                                                        return $ticket['status'] == SupportTicketEnum::STATUS_ACTIVE;
                                                    })
                                                    ->toArray();
                    
                    return $fulfillment;
                })
                ->toArray();
        
        // Get model for each of product in the fulfillment
        $paginationData = collect($allFulfillments)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        $customersList = getFormattedCustomersListForSupplier();

        return view('supplier.fulfillment.list', [
            'fulfillments' => $returnData,
            'pagination' => $paginationData,   
            'customersList' => $customersList,
            'fulfillmentStatusColors' => FulfillmentEnum::MAP_STATUS_COLORS,
            'fulfillmentStatuses' => FulfillmentEnum::MAP_FULFILLMENT_STATUSES,
            'paymentStatuses' => FulfillmentEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => FulfillmentEnum::MAP_PAYMENT_COLORS,
            'supportTicketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'shippingStatuses' => FulfillmentEnum::MAP_SHIPPING_STATUSES,
            'shippingTypes' => FulfillmentEnum::MAP_SHIPPING,
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'bulkActions' => FulfillmentEnum::MAP_BULK_ACTIONS,
            'exportRoute' => 'supplier.fulfillment.export',
            'request' => $data,
        ]);
    }

    /** Display the page for viewing fulfillment */
    public function show(Request $request, Fulfillment $fulfillment)
    {
        // Get current logged-in user
        $user = Auth::user();

        // Get customer model
        $customer = collect($fulfillment->customer)->toArray();
        
        // If this fulfillment doesn't belong to the supplier viewing, then return to previous page
        if ($user->supplier->id != $fulfillment->supplier_id) {
            // Set error message
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Get the payments recorded for this fulfillment
        $productPayments = $fulfillment->productPayments;
        $productPayments = $productPayments
                ? collect($productPayments)->map(function(FulfillmentProductPayment $productPayment) {
                    $approvedBy = $productPayment->staff;
                    $userAction = $productPayment->user;
                    // Entity owner
                    $entityAction = $userAction->getUserOwner();
                    
                    $productPayment = collect($productPayment)->toArray();
                    $productPayment['entity'] = $entityAction->toArray();

                    return $productPayment;
                })
                ->sortBy('status')
                ->toArray()
                : [];

        // Get any support ticket active for this fulfillment
        $supportTickets = $fulfillment->supportTickets;

        // Turn the fulfillment into an array
        $fulfillment = collect($fulfillment)->toArray();

        // Get product model and assign it to the fulfillment
        $fulfillment['product_configs'] = collect(unserialize($fulfillment['product_configs']))->map(function ($product) {
            // Find the product model, product group and turn it to array
            $productModel = Product::find($product['product_id']) ?? [];
            $productGroup = !empty($productModel) ? collect($productModel->productGroup)->toArray() : [];
            $product['model'] = array_merge(collect($productModel)->toArray(), ['product_group' => $productGroup]);

            // Return this product back to list
            return $product;
        })->toArray();

        // Return the view
        return view('supplier.fulfillment.show', [
            'fulfillment' => $fulfillment,
            'customer' => $customer,
            'user' => $user,
            'productPayments' => $productPayments,
            'productPaymentStatuses' => ProductPaymentEnum::MAP_STATUSES,
            'productPaymentStatusColors' => ProductPaymentEnum::MAP_STATUS_COLORS,
            'supportTicketStatuses' => SupportTicketEnum::MAP_STATUSES,
            'supportTicketStatusColors' => SupportTicketEnum::MAP_STATUS_COLORS,
        ]);
    }

    /** Handle request for export action */
    public function export(Request $request)
    {        
        $user = Auth::user();
        // Validate the request coming
        $validation = $this->validateExportRequest($request);   
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('supplier.fulfillment.list')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Get the needed data from the request
        $data = collect($request->all())->only(['selected_rows', 'export_type'])->toArray();
        
        // Prepare data for export
        foreach ($data['selected_rows'] as $fulfillmentId) {
            
            // Get the fulfillment model
            $fulfillment = Fulfillment::where([
                'id' => $fulfillmentId,
                'supplier_id' => $user->supplier->id,
            ])->first();

            // If the fulfillment is null, then add it to failed list
            if (!$fulfillment) {
                $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();
    
                return redirect()->route('supplier.fulfillment.list')->with([
                    'response' => $responseData,
                    'request' => $request->all(),
                ]);
            }
        }

        $allFulfillments = Fulfillment::whereIn('id', $data['selected_rows'])
                    ->get();

        // If we can't get the list of fulfillments, then just error out to the main page
        if ($allFulfillments->isEmpty()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::UNKNOWN_ERROR)->send();

            return redirect()->route('supplier.fulfillment.list')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // Getting the products models
        $exportData = collect($allFulfillments)
                ->map(function ($fulfillment) {
                    $fulfillment['product_configs'] = unserialize($fulfillment['product_configs']);

                    // Get product's model and its group model
                    $productConfigsPlaceholder = [];
                    foreach ($fulfillment['product_configs'] as $product) {
                        // Find the product model, product group and turn it to array
                        $productModel = Product::find($product['product_id']) ?? [];
                        $productMessage = "{$productModel->name} x {$product['quantity']}";

                        // Assign this formatted product to the list
                        $productConfigsPlaceholder[] = $productMessage;
                    }

                    $fulfillment['customer_name'] = "{$fulfillment['customer']['customer_id']} {$fulfillment['customer']['full_name']}";
                    $fulfillment['country'] = CurrencyAndCountryEnum::MAP_COUNTRIES[$fulfillment['country']] ?? ($fulfillment['country'] ?? '');
                    $fulfillment['shipping_type'] = FulfillmentEnum::MAP_SHIPPING[$fulfillment['shipping_type']] ?? 'Unknown';
                    $fulfillment['fulfillment_status'] = FulfillmentEnum::MAP_FULFILLMENT_STATUSES[$fulfillment['fulfillment_status']] ?? 'Unknown';
                    $fulfillment['shipping_status'] = FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']] ?? 'Unknown';
                    $fulfillment['product_payment_status'] = FulfillmentEnum::MAP_PAYMENT_STATUSES[$fulfillment['product_payment_status']] ?? 'Unknown';
                    $fulfillment['date_created'] = Carbon::parse($fulfillment['created_at'])->format('d/m/Y');
                    $fulfillment['products'] = implode(', ', $productConfigsPlaceholder);

                    return collect($fulfillment)->only(FulfillmentEnum::CUSTOMER_EXPORT_COLUMNS)->toArray();
                })->toArray();

        // Prepare the file for export
        $exportType = $data['export_type'] ?? GeneralEnum::EXPORT_TYPE_CSV;
        $fileName = "fulfillment_export.{$exportType}";
        $headers = array_merge(GeneralEnum::MAP_EXPORT_CONTENT_HEADERS[$exportType ?? GeneralEnum::EXPORT_TYPE_CSV], ['Content-Disposition' => "attachment; filename={$fileName}"]);
        // Columns
        $columns = collect(FulfillmentEnum::SUPPLIER_EXPORT_COLUMNS)->map(function($column) {
            return ucwords(Str::replace('_', ' ', $column));
        })->toArray();

        return response()->stream(function() use($columns, $exportData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($exportData as $row) {
                $fileRow = [
                    $row['id'] ?? '',
                    $row['products'] ?? '',
                    $row['total_product_amount'] ?? '',
                    $row['product_unit'] ?? '',
                    $row['fulfillment_status'] ?? '',
                    $row['shipping_status'] ?? '',
                    $row['name'] ?? '',
                    $row['phone'] ?? '',
                    $row['address'] ?? '',
                    $row['address2'] ?? '',
                    $row['suburb'] ?? '',
                    $row['state'] ?? '',
                    $row['postcode'] ?? '',
                    $row['country'] ?? '',
                    $row['shipping_type'] ?? '',
                    $row['tracking_number'] ?? '',
                    $row['postage'] ?? '',
                    $row['postage_unit'] ?? '',
                    $row['product_payment_status'] ?? '',
                    $row['note'] ?? '',
                    $row['date_created'] ?? '',
                ];
                fputcsv($file, $fileRow);
            }
            fclose($file);
        }, ResponseStatusEnum::CODE_SUCCESS, $headers);
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
}
