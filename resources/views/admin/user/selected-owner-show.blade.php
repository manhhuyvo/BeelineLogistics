@if (!empty($owner) && !empty($user))
    @if ($user['target'] == User::TARGET_STAFF)
        <div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-staff">
            <p class="text-lg pb-1 font-medium text-blue-400">
                Staff Assigned
            </p>
            <input type="hidden" name="staff_id">
            <div class="flex flex-col flex-1">
                <label for="staff_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['full_name'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="staff_position" class="mb-2 text-sm font-medium text-gray-900">Position</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['position'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="staff_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['status'] ?? 'Not Provided' }}</div>
            </div>
        </div>
    @elseif ($user['target'] == User::TARGET_CUSTOMER)
        <div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-customer">
            <p class="text-lg pb-1 font-medium text-blue-600">
                Customer Assigned
            </p>
            <input type="hidden" name="customer_id">
            <div class="flex flex-col flex-1">
                <label for="customer_number" class="mb-2 text-sm font-medium text-gray-900">Customer ID</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['customer_id'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="customer_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['full_name'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="customer_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['status'] ?? 'Not Provided' }}</div>
            </div>
        </div>
    @elseif ($user['target'] == User::TARGET_SUPPLIER)
        <div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-supplier">
            <p class="text-lg pb-1 font-medium text-blue-600">
                Supplier Assigned
            </p>
            <input type="hidden" name="supplier_id">
            <div class="flex flex-col flex-1">
                <label for="supplier_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['full_name'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="supplier_type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['type'] ?? 'Not Provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="supplier_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <div class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5">{{ $owner['status'] ?? 'Not Provided' }}</div>
            </div>
        </div>
    @endif
@endif