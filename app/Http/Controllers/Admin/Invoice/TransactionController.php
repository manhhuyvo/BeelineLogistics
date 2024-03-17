<?php

namespace App\Http\Controllers\Admin\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Upload;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\TransactionRepo;
use App\Enums\ResponseMessageEnum;
use App\Enums\TransactionEnum;
use App\Helpers\Validator\PaymentValidator;
use App\Repositories\PaymentRepo;

class TransactionController extends Controller
{
    use Upload;

    public function __construct(
        protected TransactionRepo $transactionRepo,
        protected PaymentRepo $paymentRepo,
        protected PaymentValidator $paymentValidator
    ) {}

    public function store(Request $request, Invoice $invoice)
    {
        /** @var User $user */
        $user = $this->getLoggedInUser();
        $rawData = collect($request->all())->except(['payment_receipt'])->toArray();

        if (!$user || !$user->isStaff() || empty($user->staff)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ACCESS)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        $validator = $this->paymentValidator->validate($request);
        if ($validator->isFailed()) {
            $responseData = viewResponseFormat()
                ->error()
                ->data($validator->getMessages())
                ->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)
                ->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        $transactionData = $this->transactionRepo->makeDataFromRequest($request, TransactionEnum::TARGET_INVOICE, $invoice->id);
        $paymentData = $this->paymentRepo->makeDataFromRequest($request);

        $response = $this->transactionRepo->addTransactionWithPayment($request, $transactionData, $paymentData);
        if (!$response) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $rawData,
            ]);
        }

        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }
}
