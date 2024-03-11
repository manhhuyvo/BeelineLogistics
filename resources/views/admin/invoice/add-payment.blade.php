<form method="POST" action="{{ route('admin.invoice.add-payment', ['invoice' => $invoice['id']]) }}" class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 flex flex-col gap-3 px-3 py-3 justify-center" enctype="multipart/form-data">
    <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
    <input type="hidden" name="invoice_id" value="{{ $invoice['id'] }}" readonly />
    <input type="hidden" name="user_id" value="{{ $user->id }}" readonly />
    <input type="hidden" name="status" value="{{ ProductPaymentEnum::STATUS_PENDING }}" readonly />
    <p class="text-lg font-medium text-blue-600 mt-1">
        Add a payment
    </p>
    <div class="row flex md:flex-row flex-col gap-2">
        <div class="flex flex-col flex-1">
            <label for="payment_method" class="mb-2 text-sm font-medium text-gray-900">Payment Method</label>
            <select id="payment_method" name="payment_method" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
            <option selected disabled>Choose a method</option>
            @foreach(ProductPaymentEnum::MAP_PAYMENT_METHODS as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
            </select>
        </div>
        <div class="flex flex-col flex-1">
            <label for="amount" class="mb-2 text-sm font-medium text-gray-900">Amount Paid</label>
            <input id="amount" type="number" step="0.01" name="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="">
        </div>
        <div class="flex flex-col flex-1">
            <label for="payment_date" class="mb-2 text-sm font-medium text-gray-900">Date Paid</label>
            <input type="date" name="payment_date" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="">
        </div>
        <div class="flex flex-col flex-1">
            <label for="description" class="mb-2 text-sm font-medium text-gray-900">Description</label>
            <input id="description" type="text" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="">
        </div>
    </div>
    <div class="flex flex-col flex-1">
        <label for="payment_receipt" class="mb-2 text-sm font-medium text-gray-900">Payment Receipt</label>
        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="file_input_help" id="file_input" type="file" name="payment_receipt">
        <p class="mt-1 text-sm text-gray-500" id="file_input_help">SVG, PNG, JPG</p>
    </div>
    <div class="row flex justify-center">
        <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
            Add Payment
        </button>
    </div>
</form>