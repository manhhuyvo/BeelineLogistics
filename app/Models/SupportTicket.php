<?php

namespace App\Models;

use App\Models\User;
use App\Models\Fulfillment;
use App\Models\Order;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = "support_tickets";

    protected $fillable = [
        'created_user_id',
        'solved_user_id',
        'solved_date',
        'content',
        'attachments',
        'status',
        'note',
    ];

    protected $casts = [
        'solved_date' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    public function userSolved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solved_user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SupportTicketComment::class, 'ticket_id', 'id');
    }

    public function fulfillments(): BelongsToMany
    {
        return $this->belongsToMany(Fulfillment::class, 'ticket_fulfillment_maps', 'ticket_id', 'fulfillment_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'ticket_order_maps', 'ticket_id', 'order_id');
    }
}
