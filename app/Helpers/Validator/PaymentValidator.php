<?php

namespace App\Helpers\Validator;

use App\Helpers\Validator\Base\BaseValidator;
use App\Helpers\Validator\Base\BaseValidatorInterface;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\PaymentEnum;

class PaymentValidator extends BaseValidator implements BaseValidatorInterface
{
    public function validate(Request $request): self
    {
        $data = $request->only([
            'amount',
            'payment_method',
            'description',
            'payment_date',
            'note',
        ]);

        $validator = Validator::make($data, [
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_method' => ['required', 'integer', Rule::in(array_keys(PaymentEnum::MAP_PAYMENT_METHODS))],
            'description' => ['required', 'string'],
            'payment_date' => ['required', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            $this->setMessages($validator->messages());

            return $this;
        }

        $this->setPassed();

        return $this;
    }
}