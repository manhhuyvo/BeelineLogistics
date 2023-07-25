<div class="row flex sm:flex-row flex-col gap-2 w-full" id="selected-owner-staff">
    <input type="hidden" name="staff_id">
    <div class="flex flex-col flex-1">
        <label for="staff_full_name" class="mb-2 text-sm font-medium text-gray-900">Name</label>
        <input id="staff_full_name" type="text" name="staff_full_name" class="bg-white border-solid border-yellow-200 text-gray-900 text-sm w-full p-2.5" value="{{ $request['full_name'] ?? '' }}" disabled>
    </div>
    <div class="flex flex-col flex-1">
        <label for="staff_position" class="mb-2 text-sm font-medium text-gray-900">Position</label>
        <input id="staff_position" type="text" name="staff_position" class="bg-white border-solid border-yellow-200 text-gray-900 text-sm w-full p-2.5" value="{{ $request['password'] ?? '' }}" disabled>
    </div>
    <div class="flex flex-col flex-1">
        <label for="staff_status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
        <input id="staff_status" type="text" name="staff_status" class="bg-white text-sm border-solid border-yellow-200 text-gray-900 w-full p-2.5" value="{{ $request['confirm_password'] ?? '' }}" disabled>
    </div>
</div>