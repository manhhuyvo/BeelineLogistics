<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepo;
use App\Models\Transaction;
use App\Enums\TransactionEnum;
use App\Repositories\PaymentRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TransactionRepo extends BaseRepo
{   
    private PaymentRepo $paymentRepo;

    public function __construct(Transaction $model, PaymentRepo $paymentRepo)
    {
        $this->model = $model;
        $this->paymentRepo = $paymentRepo;
    }

    public function getFilterableColumns(): array
    {
        return TransactionEnum::FILTERABLE_COLUMNS;
    }

    public function findAllByTarget(array $columns = ['*'], array $relations = [], ?string $target): Collection
    {
        $extra = [
            'where' => ['target', $target],
        ];

        return $this->findAll($columns, $relations, $extra);
    }

    public function findAllByTargetAndTargetId(array $columns = ['*'], array $relations = [], ?string $target, ?int $targetId): Collection
    {
        $extra = [
            'where' => ['target', $target],
            'where' => ['target_id', $targetId],
        ];

        return $this->findAll($columns, $relations, $extra);
    }

    public function findAllByTargetWithPagination(
        array $filters = [],
        array $columns = ['*'],
        array $relations = [],
        ?string $target,
        array $appends = [],
        array $paginationDetails = [50, 'page']
    ): ?LengthAwarePaginator
    {
        $extra = [
            'where' => ['target', $target],
        ];

        return $this->findAllWithPagination($filters, $columns, $relations, $extra, $appends, $paginationDetails);
    }

    public function findAllByTargetAndTargetIdWithPagination(
        array $filters = [],
        array $columns = ['*'],
        array $relations = [],
        ?string $target,
        ?int $targetId,
        array $appends = [],
        array $paginationDetails = [50, 'page']
    ): ?LengthAwarePaginator
    {
        $extra = [
            'where' => ['target', $target],
            'where' => ['target_id', $targetId],
        ];

        return $this->findAllWithPagination($filters, $columns, $relations, $extra, $appends, $paginationDetails);
    }

    public function addTransactionWithoutPayment(array $payload): ?Model
    {
        return $this->create($payload);
    }

    public function addTransactionWithPayment(Request $request, array $transactionPayload, array $paymentPayload): ?array
    {
        $transaction = $this->create($transactionPayload);

        if (!$transaction) {
            return null;
        }

        $paymentPayload = array_merge(['transaction_id' => $transaction->id], $paymentPayload);

        // If this payment has a payment receipt, then we store that file and update the path
        if ($request->hasFile('payment_receipt')) {
            $paymentReceiptPath = $this->paymentRepo->addPaymentReceipt($request, $transactionPayload['target'], $transactionPayload['target_id']);
            $paymentPayload = array_merge($paymentPayload, ['payment_receipt' => $paymentReceiptPath]);
        }

        $payment = $this->paymentRepo->create($paymentPayload);

        if (!$payment) {
            return null;
        }

        return [
            'transaction' => $transaction,
            'payment' => $payment,
        ];
    }

    public function approveTransaction(?int $id): bool
    {
        $model = $this->findById($id);

        if (!$model->payment) {
            return true;
        }

        return $this->paymentRepo->approvePayment($model->payment);
    }

    public function declineTransaction(?int $id): bool
    {
        $model = $this->findById($id);

        if (!$model->payment) {
            return true;
        }

        return $this->paymentRepo->declinePayment($model->payment);
    }

    public function pendTransaction(?int $id): bool
    {
        $model = $this->findById($id);

        if (!$model->payment) {
            return true;
        }

        return $this->paymentRepo->pendPayment($model->payment);
    }

    public function deleteTransaction(?int $id): bool
    {
        $model = $this->findById($id);

        if (!$model->payment) {
            return true;
        }

        return $this->paymentRepo->deletePayment($model->payment);
    }

    public function makeDataFromRequest(Request $request, ?string $target = null, ?int $targetId = null): array
    {
        return [
            'target' => $target,
            'target_id' => $targetId,
            'amount' => (float) $request->get('amount', 0),
            'description' => $request->get('description', ''),
            'note' => $request->get('note', ''),
            'transaction_date' => $request->get('payment_date', Carbon::now()),
        ];
    }
}