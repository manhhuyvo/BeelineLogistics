<div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-customer">
    <p class="text-lg pb-1 font-medium text-blue-600">
        Customer Assigned
    </p>
    <input type="hidden" name="customer_id" value="{{ $owner['id'] ?? '' }}">
    <div class="flex flex-col flex-1">
        <label for="customer_number" class="mb-2 text-sm font-medium text-gray-900">Customer ID</label>
        <input id="customer_number" type="text" name="customer_number" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['customer_id'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="customer_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
        <input id="customer_full_name" type="text" name="customer_full_name" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['full_name'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="customer_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
        <input id="customer_status" type="text" name="customer_status" class="bg-white text-sm border-solid border-blue-200 text-gray-900 w-full p-2.5" disabled value="{{ $owner['status'] ?? '' }}">
    </div>
</div>