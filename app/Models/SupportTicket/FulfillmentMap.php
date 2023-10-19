<?php

namespace App\Models\SupportTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fulfillment;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FulfillmentMap extends Model
{
    use HasFactory;

    protected $table = "ticket_fulfillment_maps";

    protected $fillable = [
        'fulfillment_id',
        'ticket_id',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];
}
