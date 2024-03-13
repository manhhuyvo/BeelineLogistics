<?php
namespace App\Enums;

class TransactionEnum
{
    const TARGET_INVOICE = 'invoices';
    const TARGET_BILL = 'bills';
    const TARGET_PAYCHECK = 'salary_paychecks';

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    const TRANSACTION_TARGETS = [
        self::TARGET_INVOICE,
        self::TARGET_BILL,
        self::TARGET_PAYCHECK,
    ];

    const MAP_TARGETS = [
        self::TARGET_INVOICE => 'Invoice',
        self::TARGET_BILL => 'Bill',
        self::TARGET_PAYCHECK => 'Paycheck',
    ];

    const TRANSACTION_TYPES = [
        self::TYPE_CREDIT,
        self::TYPE_DEBIT,
    ];

    const MAP_TYPES = [
        self::TYPE_CREDIT => 'Credit',
        self::TYPE_DEBIT => 'Debit',
    ];

    const FILTERABLE_COLUMNS = [
        'target',
        'target_id',
        'amount',
        'description',
        'status',
        'note',
        'transaction_date',
    ];
}