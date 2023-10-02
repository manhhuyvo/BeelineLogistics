@if(!empty($productPayments))
    <div class="w-full my-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 flex flex-col gap-3 p-3 justify-center">
        <p class="text-lg font-medium text-blue-600 mt-1">
            Payment Records
        </p>
        <div class="w-full flex flex-col gap-4 px-2">
        @if ($user->isStaff() && $user->staff->isAdmin())
            @foreach ($productPayments as $productPayment)
            <form method="POST" action="{{ route('admin.fulfillment.update-payment', ['fulfillment' => $fulfillment['id']]) }}" class="w-full flex flex-col gap-3 pb-3 border-solid border-[1px] border-gray-200 cursor-pointer shadow-lg rounded-lg my-1" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
                <input type="hidden" name="payment_id" value="{{ $productPayment['id'] }}" />
                <div class="w-full py-2 text-center text-white text-sm font-bold bg-{{ $productPaymentStatusColors[$productPayment['status']] }}-500 rounded-t-lg">
                    {{ Str::upper($productPaymentStatuses[$productPayment['status']]) }}
                </div>
                <div class="w-full flex md:flex-row flex-col justify-between items-center px-3 text-sm font-medium text-gray-400">
                    <div>Date Added: {{ $productPayment['created_at'] }}</div>
                    @if ($productPayment['status'] != ProductPaymentEnum::STATUS_PENDING)
                        <div class="flex gap-2 text-{{ $productPaymentStatusColors[$productPayment['status']] }}-500">{{ $productPaymentStatuses[$productPayment['status']] }}: {{ $productPayment['staff']['full_name'] ?? 'Not Known' }} ({{ $productPayment['updated_at'] ?? '' }})</div>
                    @endif
                </div>
                <div class="flex md:flex-row flex-col gap-2 flex-1 justify-start px-3">
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Payment Method</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ ProductPaymentEnum::MAP_PAYMENT_METHODS[$productPayment['payment_method']] ?? 'Not Provided' }}</div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Amount Paid</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['amount'] ?? 0.00 }}</div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Date Paid</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['payment_date'] ?? '' }}</div>
                    </div>
                </div>
                <div class="flex md:flex-row flex-col gap-2 flex-1 justify-start px-3">
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Description</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['description'] ?? '' }}</div>
                    </div>
                </div>
                <div class="flex md:flex-row flex-col md:justify-between gap-3 justify-center w-full px-3">
                    @php
                        $paymentReceipt = $productPayment['payment_receipt'];
                    @endphp
                    <a href="{{ url("fulfillment_payment_receipts/$paymentReceipt") }}" target="_blank" class="flex flex-row items-center gap-2 text-sm text-blue-500 font-semibold hover:underline">View Payment Receipt<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                    <div class="flex flex-row gap-2 justify-center">
                        @if ($productPayment['status'] != ProductPaymentEnum::STATUS_APPROVED)
                        <input type="submit" class="px-3 py-2 rounded-[5px] text-[12px] bg-green-600 text-white font-medium w-auto hover:bg-green-500 flex items-center gap-2" name="update_payment" value="Approve">
                        @endif
                        @if (in_array($productPayment['status'], [ProductPaymentEnum::STATUS_APPROVED, ProductPaymentEnum::STATUS_PENDING]))
                        <input type="submit" class="px-3 py-2 rounded-[5px] text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2" name="update_payment" value="Decline">
                        @endif
                    </div>
                </div>
            </form>
            @endforeach
        @else
            @foreach ($productPayments as $productPayment)
            <div class="w-full flex flex-col gap-3 pb-3 border-solid border-[1px] border-gray-200 cursor-pointer shadow-lg rounded-lg my-1">
                <div class="w-full py-2 text-center text-white text-sm font-bold bg-{{ $productPaymentStatusColors[$productPayment['status']] }}-500 rounded-t-lg">
                    {{ Str::upper($productPaymentStatuses[$productPayment['status']]) }}
                </div>
                <div class="flex md:flex-row flex-col gap-2 flex-1 justify-start px-3">
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Payment Method</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ ProductPaymentEnum::MAP_PAYMENT_METHODS[$productPayment['payment_method']] ?? 'Not Provided' }}</div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Amount Paid</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['amount'] ?? 0.00 }}</div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Date Paid</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['payment_date'] ?? '' }}</div>
                    </div>
                </div>
                <div class="flex md:flex-row flex-col gap-2 flex-1 justify-start px-3">
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Description</label>
                        <div class="bg-gray-50 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $productPayment['description'] ?? '' }}</div>
                    </div>
                </div>
                <div class="flex md:flex-row flex-col md:justify-between gap-3 justify-center w-full px-3">
                    @php
                        $paymentReceipt = $productPayment['payment_receipt'];
                    @endphp
                    <a href="{{ url("fulfillment_payment_receipts/$paymentReceipt") }}" target="_blank" class="flex flex-row items-center gap-2 text-sm text-blue-500 font-semibold hover:underline">View Payment Receipt<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                </div>
            </div>
            @endforeach
        @endif
        </div>
    </div>
@else
<p class="text-sm w-full text-center text-red-500 font-semibold">There is no payments recorded for this fulfillment.</p>
@endif