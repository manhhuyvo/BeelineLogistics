<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Fulfillment;

class Item extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'order_id',
        'fulfillment_id',
        'description',
        'price',
        'unit',
        'quantity',
        'amount',
        'note',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function fulfillment(): BelongsTo
    {
        return $this->belongsTo(Fulfillment::class, 'fulfillment_id', 'id');
    }

    public static function boot()
    {        
        parent::boot();

        static::updating(function($item) {
            $invoiceItemBeforeUpdate = Item::find($item->id);
            // If either price or quantity is updated, then we calculate the amount again
            if ($invoiceItemBeforeUpdate->price != $item->price || $invoiceItemBeforeUpdate->quantity != $item->quantity) {
                // Save the new amount
                $newAmount = (float) $item->price * $item->quantity;
                $item->amount = (float) $newAmount;

                // Get the current amount of this invoice item
                $currentInvoiceItemAmount = (float) $invoiceItemBeforeUpdate->amount;
                // Update total amount of the invoice that this invoice item belongs to
                $invoice = $item->invoice;
                $invoice->total_amount = $invoice->total_amount - $currentInvoiceItemAmount + $item->amount;
                $invoice->outstanding_amount = $invoice->outstanding_amount - $currentInvoiceItemAmount + $item->amount;
                $invoice->save();
            }
        });
    }
}
