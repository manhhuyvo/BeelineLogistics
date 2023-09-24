@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View Invoice Details</h2>
    <h2 class="text-xl font-bold absolute top-0 right-0 text-{{ $paymentStatusColors[$invoice['payment_status']] }}-500 px-3 py-2 border-solid border-{{ $paymentStatusColors[$invoice['payment_status']] }}-500 border-[3px] rounded-lg">{{ Str::upper($paymentStatuses[$invoice['payment_status']]) }}</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <!-- INVOICE DETAILS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Invoice Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_search" class="mb-2 text-sm font-medium text-gray-900">Customer Owner</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['customer_id'] }} - {{ $customer['full_name'] }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="due_date" class="mb-2 text-sm font-medium text-gray-900">Due Date</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $invoice['due_date'] }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="reference" class="mb-2 text-sm font-medium text-gray-900">Reference</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ htmlspecialchars($invoice['reference']) }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="unit" class="mb-2 text-sm font-medium text-gray-900">Invoice Currency</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $invoice['unit'] }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Invoice Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ $invoiceStatusColors[$invoice['status']] }}-600 text-sm w-full py-2.5 px-2">{{ $invoiceStatuses[$invoice['status']] ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $invoice['note'] ?? '' }}</div>
                </div>
            </div>
            <!-- INVOICE ITEMS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Invoice Items
            </p>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 min-width">
                    <thead class="text-sm text-gray-500 font-semibold bg-gray-200"  style="text-align: center !important;">
                        <tr>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-25">
                                Item Target
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-50">
                                Description
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-5">
                                Price
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-5">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 w-5">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center !important;" id="add_new_row_container">
                        @foreach ($invoice['items'] as $invoiceItem)
                        <tr class="bg-white border-b hover:bg-gray-50 invoice-row">
                            <td scope="row" class="px-2 py-2 text-center font-normal flex flex-col justify-start gap-2">
                                <div class="w-full flex items-center text-gray-900 text-sm relative">
                                    <p class="font-semibold">{{ Str::upper($invoiceItem['item_type']) }}</p>
                                </div>
                                @if ($invoiceItem['item_type'] == InvoiceEnum::TARGET_FULFILLMENT)
                                <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
                                    <div class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg w-full sm:p-2.5 p-1.5">Fulfillment #{{ $invoiceItem['fulfillment_id'] }}</div>
                                </div>
                                @elseif ($invoiceItem['item_type'] == InvoiceEnum::TARGET_ORDER)
                                <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
                                    <div class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg w-full sm:p-2.5 p-1.5">Order #{{ $invoiceItem['order_id'] }}</div>
                                </div>
                                @endif
                            </td>
                            <td scope="row" class="px-2 py-2 font-normal text-gray-900">
                                <div class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2 resize-none h-[150px] overflow-y-auto text-left whitespace-pre-line">{{ $invoiceItem['description'] ?? '' }}</div>
                            </td>
                            <td scope="row" class="px-2 py-2 font-normal">
                                <div class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 text-center min-h-[150px]">{{ $invoiceItem['price'] ?? '' }}</div>
                            </td>
                            <td scope="row" class="px-2 py-2 font-normal">
                                <div class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 text-center min-h-[150px]" style="height: 100% !important">{{ $invoiceItem['quantity'] ?? '' }}</div>
                            </td>
                            <td scope="row" class="px-2 py-2 font-normal">
                                <div class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 text-center min-h-[150px]">{{ $invoiceItem['amount'] ?? '' }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-normal text-gray-900">
                            <td class="p-2.5"></td>
                            <td class="p-2.5"></td>
                            <td class="p-2.5">Items</td>
                            <td class="p-2.5 text-center">x</td>
                            <td class="p-2.5 text-center" id="total-item-count">{{ count($invoice['items']) }}</td>
                        </tr>
                        <tr class="font-normal text-gray-900">
                            <td class="p-2.5"></td>
                            <td class="p-2.5"></td>
                            <td class="p-2.5">Sub-total</td>
                            <td class="p-2.5 text-center"><span class="total-currency">{{ $invoice['unit'] }}</span></td>
                            <td class="p-2.5 text-center"><span class="total-amount">{{ $invoice['total_amount'] }}</span></td>
                        </tr>
                        <tr class="font-semibold text-gray-900 text-lg">
                            <td class="p-2.5"></td>
                            <td class="p-2.5"></td>
                            <th scope="row" class="p-2.5 border-t border-gray-500">Total</th>
                            <td class="p-2.5 border-t border-gray-500 text-center"><span class="total-currency">{{ $invoice['unit'] }}</span></td>
                            <td class="p-2.5 border-t border-gray-500 text-center"><span class="total-amount">{{ $invoice['total_amount'] }}</span></td>
                        </tr>
                        @if ($invoice['outstanding_amount'] == 0)
                        <tr class="font-semibold text-green-500 text-lg">
                        @else
                        <tr class="font-semibold text-red-500 text-lg">
                        @endif
                            <td class="p-2.5"></td>
                            <td class="p-2.5"></td>
                            <th scope="row" class="p-2.5">Outstanding</th>
                            <td class="p-2.5 text-center"><span class="total-currency">{{ $invoice['unit'] }}</span></td>
                            <td class="p-2.5 text-center"><span class="total-amount">{{ $invoice['outstanding_amount'] }}</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.invoice.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.invoice.edit.form', ['invoice' => $invoice['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <form method="POST" action={{ route('admin.invoice.add-payment', ['invoice' => $invoice['id']]) }} class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 flex flex-col gap-3 px-3 py-3 justify-center" enctype="multipart/form-data">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
        <input name="staff_id" type="hidden" value="{{ $user->id }}" />
        <input name="type" type="hidden" value="invoice"/>
        <input name="invoice_id" type="hidden" value="{{ $invoice['id'] }}" />
        <!-- INVOICE DETAILS -->
        <p class="text-lg font-medium text-blue-600 mt-1">
            Add a payment
        </p>
        <div class="row flex md:flex-row flex-col gap-2">
            <div class="flex flex-col flex-1">
                <label for="payment_method" class="mb-2 text-sm font-medium text-gray-900">Payment Method</label>
                <select id="payment_method" name="payment_method" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                <option selected disabled>Choose a method</option>
                @foreach(CurrencyAndCountryEnum::MAP_CURRENCIES as $key => $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="amount" class="mb-2 text-sm font-medium text-gray-900">Amount Paid</label>
                <input id="amount" type="number" step="2" name="amount_paid" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="">
            </div>
            <div class="flex flex-col flex-1">
                <label for="payment_date" class="mb-2 text-sm font-medium text-gray-900">Date Paid</label>
                <input type="date" name="payment_date" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="">
            </div>
            <div class="flex flex-col flex-1">
                <label for="description" class="mb-2 text-sm font-medium text-gray-900">Description</label>
                <input id="transaction_reference" type="text" name="transaction_reference" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="">
            </div>
        </div>
        <div class="flex flex-col flex-1">
            <label for="payment_receipt" class="mb-2 text-sm font-medium text-gray-900">Payment Receipt</label>
            @include('admin.invoice.file-drag-drop')
        </div>
        <div class="row flex justify-center">
            <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                Add Payment
            </button>
        </div>
    </form>
</div>
@endsection