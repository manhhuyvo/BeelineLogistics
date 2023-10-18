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
}
