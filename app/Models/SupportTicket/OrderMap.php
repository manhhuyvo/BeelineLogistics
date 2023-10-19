<?php

namespace App\Models\SupportTicket;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SupportTicket;

class OrderMap extends Model
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
