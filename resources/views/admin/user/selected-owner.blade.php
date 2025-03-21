<div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-staff">
    <p class="text-lg pb-1 font-medium text-blue-400">
        Staff Assigned
    </p>
    <input type="hidden" name="staff_id" value="{{ $owner['id'] ?? '' }}">
    <div class="flex flex-col flex-1">
        <label for="staff_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
        <input id="staff_full_name" type="text" name="staff_full_name" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['full_name'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="staff_position" class="mb-2 text-sm font-medium text-gray-900">Position</label>
        <input id="staff_position" type="text" name="staff_position" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['position'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="staff_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
        <input id="staff_status" type="text" name="staff_status" class="bg-white text-sm border-solid border-blue-200 text-gray-900 w-full p-2.5" disabled value="{{ $owner['status'] ?? '' }}">
    </div>
</div>
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
<div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-supplier">
    <p class="text-lg pb-1 font-medium text-blue-600">
        Supplier Assigned
    </p>
    <input type="hidden" name="supplier_id" value="{{ $owner['id'] ?? '' }}">
    <div class="flex flex-col flex-1">
        <label for="supplier_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
        <input id="supplier_full_name" type="text" name="supplier_full_name" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['full_name'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="supplier_type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
        <input id="supplier_type" type="text" name="supplier_type" class="bg-white border-solid border-blue-200 text-gray-900 text-sm w-full p-2.5" disabled value="{{ $owner['type'] ?? '' }}">
    </div>
    <div class="flex flex-col flex-1">
        <label for="supplier_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
        <input id="supplier_status" type="text" name="supplier_status" class="bg-white text-sm border-solid border-blue-200 text-gray-900 w-full p-2.5" disabled value="{{ $owner['status'] ?? '' }}">
    </div>
</div>