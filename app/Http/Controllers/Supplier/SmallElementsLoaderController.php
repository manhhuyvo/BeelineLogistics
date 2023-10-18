<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\SupportTicketEnum;

class SmallElementsLoaderController extends Controller
{
    public function getNewTicketBelongsRow($target)
    {
        if (!empty($target) && $target == SupportTicketEnum::TARGET_FULFILLMENT) {
            $targetsList = getFormattedFulfillmentsList();
        } else if (!empty($target) && $target == SupportTicketEnum::TARGET_ORDER) {
            $targetsList = getFormattedOrdersList();
        }
        
        return view('supplier.small_elements.ticket-belongs-row', [
            'targetsList' => $targetsList ?? [],
            'target' => $target,
        ]);
    }
}
