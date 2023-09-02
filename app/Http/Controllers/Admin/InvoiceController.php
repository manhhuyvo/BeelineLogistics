<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice\Item as InvoiceItem;
use App\Models\Fulfillment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Enums\GeneralEnum;
use App\Enums\ResponseStatusEnum;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {

    }

    public function validateRequest(Request $request)
    {

    }

    public function formatRequestData(Request $request)
    {
        
    }
}
