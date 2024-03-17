<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Customer;
use App\Models\Invoice\Item as InvoiceItem;
use App\Enums\InvoiceEnum;
use App\Enums\TransactionEnum;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'reference',
        'total_amount',
        'outstanding_amount',
        'due_date',
        'unit',
        'status',
        'payment_status',
        'note',
    ];

    protected $casts = [
        'due_date' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'target_id', 'id')
            ->where('target', '=', TransactionEnum::TARGET_INVOICE);
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($item) {
            // If status is cancel or waived, we set outstanding amount to 0
            if (in_array($item->status, InvoiceEnum::NO_OUTSTANDING_STATUSES)) {
                $item->outstanding_amount = 0.00;
            }

            // If also the status payment is paid, we set outstanding amount to 0
            if ($item->status == InvoiceEnum::STATUS_PAID) {
                $item->outstanding_amount = 0.00;
            }

            // Later on if these statuses get changed to other statuses, we get the real outstanding_amount by minusing the total payments received with the invoice total amount
        });
    }
}
