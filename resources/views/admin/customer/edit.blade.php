@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Edit customer details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.customer.update', $customer['id']) }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Customer Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer ID</label>
                    <input id="customer_id" type="text" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Customer ID" value="{{ $customer['customer_id'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="full_name" type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $customer['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $customer['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $customer['address'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="company" class="mb-2 text-sm font-medium text-gray-900">Company <span class="text-gray-400">(Optional)</span></label>
                    <input id="company" type="text" name="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Company" value="{{ $customer['company'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                @if ($user->staff->isAdmin())
                <div class="flex flex-col flex-1">
                    <label for="staff_id" class="mb-2 text-sm font-medium text-gray-900">Staff Manage</label>
                    <select id="staff_id" name="staff_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($customer['staff_id']))
                        <option selected disabled>Choose a staff</option>
                        @endif
                    @foreach($staffsList as $key => $value)
                        @if (!empty($customer['staff_id']) && $customer['staff_id'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="staff_id" value="{{ $user->staff->id }}" readonly />
                @endif
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($customer['type']))
                        <option selected disabled>Choose a type</option>
                        @endif
                    @foreach($customerTypes as $key => $value)
                        @if (!empty($customer['type']) && $customer['type'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($customer['status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($customerStatuses as $key => $value)
                        @if (!empty($customer['status']) && $customer['status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Extra note" value="{{ $customer['note'] ?? '' }}">{{ $customer['note'] ?? '' }}</textarea>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Sender
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="default_sender_name" type="text" name="default_sender_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Full Name" value="{{ $customer['default_sender']['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_sender_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="default_sender_phone" type="text" name="default_sender_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Phone" value="{{ $customer['default_sender']['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="default_sender_address" type="text" name="default_sender_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Address" value="{{ $customer['default_sender']['address'] ?? '' }}">
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Receiver
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="default_receiver_name" type="text" name="default_receiver_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Full Name" value="{{ $customer['default_receiver']['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="default_receiver_phone" type="text" name="default_receiver_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Phone" value="{{ $customer['default_receiver']['phone'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_zone" class="mb-2 text-sm font-medium text-gray-900">Zone</label>
                    <select id="default_receiver_zone" name="default_receiver_zone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($customer['default_receiver']['zone']))
                        <option selected disabled>Choose a zone</option>
                        @endif
                    @foreach($receiverZones as $key => $value)
                        @if (!empty($customer['default_receiver']['zone']) && $customer['default_receiver']['zone'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="default_receiver_address" type="text" name="default_receiver_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Receiver Address" value="{{ $customer['default_receiver']['address'] ?? '' }}">
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Update
                </button>
                <a href="{{ route('admin.customer.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    @include('admin.customer.country-config')
    @include('admin.customer.service-config')
</div>

<script>
    const selected = 'bg-gray-300 text-gray-800';
    const notSelected = 'bg-white text-gray-500';
</script>
@endsection