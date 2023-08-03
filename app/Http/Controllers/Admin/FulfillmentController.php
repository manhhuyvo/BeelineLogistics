<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Product;
use App\Models\Fulfillment;
use App\Enums\FulfillmentEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;

class FulfillmentController extends Controller
{
    public function create(Request $request)
    {
        return view('admin.fulfillment.create', [
            'fulfillmentStatuses' => FulfillmentEnum::MAP_FULFILLMENT_STATUSES,
            'fulfillmentStatusColors' => FulfillmentEnum::MAP_STATUS_COLORS,
            'paymentStatuses' => FulfillmentEnum::MAP_PAYMENT_STATUSES,
            'paymentStatusColors' => FulfillmentEnum::MAP_PAYMENT_COLORS,
            'countries' => FulfillmentEnum::MAP_COUNTRIES,
        ]);
    }
}
