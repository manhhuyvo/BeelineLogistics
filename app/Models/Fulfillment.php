<?php

namespace App\Models;

use App\Enums\FulfillmentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice\Item as InvoiceItem;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\InvoiceEnum;
use Exception;

class Fulfillment extends Model
{
    use HasFactory;

    protected $table = 'fulfillments';

    protected $fillable = [
        'product_configs',
        'customer_id',
        'staff_id',
        'supplier_id',
        'name',
        'phone',
        'address',
        'address2',
        'suburb',
        'state',
        'postcode',
        'country',
        'shipping_type',
        'postage',
        'postage_unit',
        'tracking_number',
        'total_product_amount',
        'product_unit',
        'total_labour_amount',
        'labour_unit',
        'fulfillment_status',
        'product_payment_status',
        'labour_payment_status',
        'shipping_status',
        'note',
    ];

    protected $casts = [
        'product_configs' => 'array',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'fulfillment_id', 'id');
    }

    public function productPayments(): HasMany
    {
        return $this->hasMany(FulfillmentProductPayment::class, 'fulfillment_id', 'id');
    }    

    public function supportTickets(): BelongsToMany
    {
        return $this->belongsToMany(SupportTicket::class, 'ticket_fulfillment_maps', 'fulfillment_id', 'ticket_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($item) {
            try {
                if (!in_array($item->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES)) {
                    $productConfigs = $item->getProductsModelAndQuantities();

                    // Update product current' stock
                    foreach ($productConfigs as $productId => $quantityDetails) {
                        $quantityDetails['model']->stock = $quantityDetails['model']->stock - (int) $quantityDetails['quantity'];
                        $quantityDetails['model']->save();
                    }
                }
            } catch (Exception $e) {
                // Add some log here
                dd($e->getMessage());
            }
        });

        static::updating(function($item) {
            $fulfillmentBeforeUpdate = Fulfillment::find($item->id);
            $productStockUpdated = false;

            // If the status was updated to a different value, then we check if we need to return stock or take stock
            if ($fulfillmentBeforeUpdate->fulfillment_status != $item->fulfillment_status) {
                // If old status is active and new status is inactive, then we return stock by the quantity of the old details
                if (!in_array($fulfillmentBeforeUpdate->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES) && in_array($item->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES)) {
                    $fulfillmentBeforeUpdate->returnProductStock();
                    $productStockUpdated = true;
                }
                
                // Otherwise if old status is inactive and new status is active, then we take stock by the quantity of new details
                if (in_array($fulfillmentBeforeUpdate->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES) && !in_array($item->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES)) {
                    $item->takeProductStock();
                    $productStockUpdated = true;
                }
            }

            // If the product wasn't updated from the above actions, that mean we need to check if any products or quantities are different, then we update the product stock accordingly
            if (!$productStockUpdated && !in_array($item->fulfillment_status, FulfillmentEnum::FULFILLMENT_INACTIVE_STATUSES)) {
                try {
                    $productWithDifferentQuantities = $fulfillmentBeforeUpdate->getProductConfigsBeforeAndAfter($item);
                    // If the diff list is not empty, then we loop through each product and update the stock accordingly
                    if (!empty($productWithDifferentQuantities)) {
                        foreach ($productWithDifferentQuantities as $productId => $quantityDetails) {
                            $product = Product::find($productId);
                            // If for some reasons we cannot find the product
                            if (!$product) {
                                continue;
                            }
    
                            // Handle logic for updating product's stock as: stock = stock + old - new
                            $product->stock = (int) $product->stock + (int) $quantityDetails['old'] - (int) $quantityDetails['new'];
                            $product->save();
                        }
                    }
                } catch (Exception $e) {
                    // Add some log here
                    dd($e->getMessage());
                }
            }

            // Always update the invoice item every time the fulfillment is updated because we may have changed some details in the description
            $invoiceItemsList = $item->invoiceItems;
            $newDescription = $item->getInvoiceItemDescriptionFromFulfillmentDetails();
            foreach ($invoiceItemsList as $invoiceItem) {
                // Update invoice item amount with the new labour amount
                $invoiceItem->price = (float) $item->total_labour_amount;
                $invoiceItem->description = $newDescription;
                $invoiceItem->save();
            }
        });
    }

    // Get quantity and product as model
    private function getProductsModelAndQuantities()
    {
        $returnData = [];
        try {
            $productConfigs = unserialize($this->product_configs);
    
            foreach ($productConfigs as $productId => $details) {
                $product = Product::find($productId);
                $details['model'] = $product;
                $returnData[$productId] = $details;
            }
        } catch (Exception $e) {
            // Add some log here
            dd($e->getMessage());
        }

        return $returnData;
    }

    // Get the products configs of fulfillment before and after changes and format them
    private function getProductConfigsBeforeAndAfter(Fulfillment $after)
    {
        $finalData = [];
        try {
            $productConfigsBefore = unserialize($this->product_configs);
            $productConfigsAfter = unserialize($after->product_configs);

            foreach ($productConfigsBefore as $productId => $details) {
                // If this product has been removed in the new list
                if (empty($productConfigsAfter[$productId])) {
                    $finalData[$productId] = [
                        'old' => $details['quantity'],
                        'new' => 0,
                    ];

                    continue;
                }

                // Otherwise if this product is still in the new list, then we check if the quantities are different. If not different, then we dont need to add to the list as we won't make any changes on that product
                if ($details['quantity'] == $productConfigsAfter[$productId]['quantity']) {
                    continue;
                }

                $finalData[$productId] = [
                    'old' => $details['quantity'],
                    'new' => $productConfigsAfter[$productId]['quantity'],
                ];
            }

            foreach ($productConfigsAfter as $productId => $details) {
                // If this product in new list also exist in the previous list, then just ignore it as we already put it to the final list
                if (!empty($productConfigsBefore[$productId])) {
                    continue;
                }

                // Otherwise if this product doesn't exist in the previous list, we add it to the final list
                $finalData[$productId] = [
                    'old' => 0,
                    'new' => $productConfigsAfter[$productId]['quantity'],
                ];
            }
        } catch (Exception $e) {
            // Add some log here
            dd($e->getMessage());
        }

        return $finalData;
    }

    // Put the quantity back to product stock on fulfillment active to inactive
    private function returnProductStock()
    {
        try {
            $productConfigs = $this->getProductsModelAndQuantities();

            foreach ($productConfigs as $productId => $product) {
                $product['model']->stock = (int) $product['model']->stock + (int) $product['quantity'];
                $product['model']->save();
            }
        } catch (Exception $e) {
            // Add some log here
            dd($e->getMessage());
        }
    }

    // Take the quantity out of product stock on fulfillment inactive to active
    private function takeProductStock()
    {
        try {
            $productConfigs = $this->getProductsModelAndQuantities();

            // Update product current's stock

            foreach ($productConfigs as $productId => $product) {
                $product['model']->stock = (int) $product['model']->stock - (int) $product['quantity'];
                $product['model']->save();
            }
        } catch (Exception $e) {
            // Add some log here
            dd($e->getMessage());
        }
    }

    // Get the invoice item description with the new fulfillment details
    private function getInvoiceItemDescriptionFromFulfillmentDetails()
    {        
        // Fulfillment's receiver details
        $fulfillmentId = $this->id;
        $fulfillmentName = $this->name ?? "Not Provided";
        $fulfillmentPhone = $this->phone ?? "Not Provided";
        $fulfillmentAddress2 = !empty($this->address2) ? " {$this->address2}," : "";
        $fulfillmentCountry = CurrencyAndCountryEnum::MAP_COUNTRIES[$this->country ?? ''] ?? '';
        $fulfillmentAddress = "{$this->address},{$fulfillmentAddress2} {$this->suburb} {$this->state} {$this->postcode} {$fulfillmentCountry}";
        $fulfillmentProductDetails = !empty($this->total_product_amount) ? "{$this->total_product_amount} {$this->product_unit}" : "Not Provided";
        $fulfillmentPostageDetails = !empty($this->postage) ? "{$this->postage} {$this->postage_unit}" : "Not Provided";
        $fulfillmentLabourDetails = !empty($this->total_labour_amount) ? "{$this->total_labour_amount} {$this->labour_unit}" : "Not Provided";

        return InvoiceEnum::MAP_DESCRIPTION_TARGET[InvoiceEnum::TARGET_FULFILLMENT] . " Details of Fulfillment:\n- Fulfillment ID: #{$fulfillmentId}\n- Receiver Name: {$fulfillmentName}\n- Receiver Phone: {$fulfillmentPhone}\n- Receiver Address: {$fulfillmentAddress}\n- Product Amount: {$fulfillmentProductDetails}\n- Labour Amount: {$fulfillmentLabourDetails}\n- Postage: {$fulfillmentPostageDetails}";
    }
}
