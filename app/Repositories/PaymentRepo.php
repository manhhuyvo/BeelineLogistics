<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepo;
use App\Models\Payment;
use App\Enums\PaymentEnum;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class PaymentRepo extends BaseRepo
{    
    use Upload;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return PaymentEnum::FILTERABLE_COLUMNS;
    }

    public function addPaymentReceipt(Request $request, ?string $target, ?int $targetId): ?string
    {
        $path = '';
        // Check if the file has been uploaded
        if ($request->hasFile('payment_receipt')) {
            // Prepare some values to save to database and store image
            $today = Carbon::now()->format('d_m_Y');
            $randomString = generateRandomString(8);
            $fileName = "{$target}_{$targetId}_payment_receipt_{$today}_{$randomString}";
            $path = $this->UploadFile($request->file('payment_receipt'), null, 'public', $fileName);
            // The file which has been uploaded
            $uploadedFile = $request->file('payment_receipt');

            // Save the file to public/fulfillment_payment_receipts folder
            $uploadedFile->move(public_path() . "/{$target}_payment_receipts", $path);
        }

        return $path;
    }

    public function approvePayment(Model $model): bool
    {
        return $model->update(['status' => PaymentEnum::STATUS_APPROVED]);
    }

    public function declinePayment(Model $model): bool
    {
        return $model->update(['status' => PaymentEnum::STATUS_DECLINED]);
    }

    public function pendPayment(Model $model): bool
    {
        return $model->update(['status' => PaymentEnum::STATUS_PENDING]);
    }

    public function deletePayment(Model $model): bool
    {
        return $model->update(['status' => PaymentEnum::STATUS_DELETED]);
    }
}