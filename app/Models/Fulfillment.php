<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Product;

class Fulfillment extends Model
{
    use HasFactory;

    protected $table = 'fulfillments';

    protected $fillable = [
        'product_configs',
        'customer_id',
        'staff_id',
        'name',
        'phone',
        'address',
        'address2',
        'suburb',
        'state',
        'postcode',
        'country',
        'labour_configs',
        'shipping_type',
        'postage',
        'tracking_number',
        'total_product_amount',
        'total_labour_amount',
        'fulfillment_status',
        'product_payment_status',
        'labour_payment_status',
        'note',
    ];

    protected $casts = [
        'product_configs' => 'array',
        'labour_configs' => 'array',
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
}
