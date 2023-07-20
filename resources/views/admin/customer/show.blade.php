@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View customer details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Customer Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer ID</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['customer_id'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['phone'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['address'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="company" class="mb-2 text-sm font-medium text-gray-900">Company</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['company'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="staff_id" class="mb-2 text-sm font-medium text-gray-900">Staff Manage</label>
                    <div class="bg-gray-50 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffsList[$customer['staff_id']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <div class="bg-gray-50 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $customerTypes[$customer['type']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ $customerStatusColors[$customer['status']] ?? 'red' }}-500 text-sm w-full py-2.5 px-2">{{ $customerStatuses[$customer['status']] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $customer['note'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Sender
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_sender_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['phone'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['address'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Receiver
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['phone'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_zone" class="mb-2 text-sm font-medium text-gray-900">Zone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $receiverZones[$customer['default_receiver']['zone']] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['address'] ?? 'Not provided' }}</div>
                </div>
            </div>
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center md:px-3 gap-2 w-full">
        <a href="{{ route('admin.customer.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.customer.edit.form', ['customer' => $customer['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

@endsection