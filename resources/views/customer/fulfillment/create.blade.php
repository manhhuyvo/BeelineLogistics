@extends('customer.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('customer.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Add new fulfillment</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('customer.fulfillment.store') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <!-- FULFILLMENT DETAILS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Fulfillment Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="fulfillment_number" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Number</label>
                    <input id="fulfillment_number" type="text" name="fulfillment_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Fulfillment Number" value="{{ $request['fulfillment_number'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="name" type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $request['name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $request['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $request['address'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="address2" class="mb-2 text-sm font-medium text-gray-900">Address 2</label>
                    <input id="address2" type="text" name="address2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address 2" value="{{ $request['address2'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="suburb" class="mb-2 text-sm font-medium text-gray-900">Suburb</label>
                    <input id="suburb" type="text" name="suburb" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Suburb" value="{{ $request['suburb'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="state" class="mb-2 text-sm font-medium text-gray-900">State</label>
                    <input id="state" type="text" name="state" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="State" value="{{ $request['state'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="postcode" class="mb-2 text-sm font-medium text-gray-900">Postcode</label>
                    <input id="postcode" type="text" name="postcode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Postcode" value="{{ $request['postcode'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="country" class="mb-2 text-sm font-medium text-gray-900">Country</label>
                    <select id="country" name="country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['country']))
                        <option selected disabled>Choose a country</option>
                        @endif
                    @foreach($countries as $key => $value)
                        @if (!empty($request['country']) && $request['country'] == $key)
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
                    <label for="fulfillment_status" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Status</label>
                    <select id="fulfillment_status" name="fulfillment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['fulfillment_status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($fulfillmentStatuses as $key => $value)
                        @if (!empty($request['fulfillment_status']) && $request['fulfillment_status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="product_payment_status" class="mb-2 text-sm font-medium text-gray-900">Product Payment Status</label>
                    <select id="product_payment_status" name="product_payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['product_payment_status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($paymentStatuses as $key => $value)
                        @if (!empty($request['product_payment_status']) && $request['product_payment_status'] == $key)
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
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Extra note" value="{{ $request['note'] ?? '' }}">{{ $request['note'] ?? '' }}</textarea>
                </div>
            </div>
            <!-- SHIPPING INFORMATION -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Shipping Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="shipping_status" class="mb-2 text-sm font-medium text-gray-900">Shipping Status</label>
                    <select id="shipping_status" name="shipping_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['shipping_status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach(FulfillmentEnum::MAP_SHIPPING_STATUSES as $key => $value)
                        @if (!empty($request['shipping_status']) && $request['shipping_status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="shipping_type" class="mb-2 text-sm font-medium text-gray-900">Shipping Type</label>
                    <select id="shipping_type" name="shipping_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['shipping_type']))
                        <option selected disabled>Choose a shipping</option>
                        @endif
                    @foreach(FulfillmentEnum::MAP_SHIPPING as $key => $value)
                        @if (!empty($request['shipping_type']) && $request['shipping_type'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="tracking_number" class="mb-2 text-sm font-medium text-gray-900">Tracking Number</label>
                    <input id="tracking_number" type="text" name="tracking_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Tracking Number" value="{{ $request['tracking_number'] ?? '' }}">
                </div>
            </div>
            <!-- PRODUCTS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Products Details
            </p>
            <div class="row flex flex-col gap-2 px-2.5 justify-center" id="add_new_row_container">
            </div>
            <div class="row flex flex-row gap-2 px-2.5">
                <button type="button" id="add_new_row" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-blue-800 text-white font-medium w-auto hover:bg-blue-700 flex items-center gap-2 w-fit">
                    <i class="fa-solid fa-plus"></i>
                    Add product
                </button>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Create
                </button>
                <a href="{{ route('customer.fulfillment.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const searchCustomerInput = $('#customer_search');
    const ajaxCustomerSearchResult = $('#ajax-customer-search-result');

    // Owner section variables
    const selectedCustomerOwner = $('#selected-customer-owner')
    const customerOwnerDiv = $('#selected-owner-customer')

    // Add new row
    const addNewRowBtn = $('#add_new_row')
    const addNewRowContainer = $('#add_new_row_container')

    $(document).ready(function() {
        addNewRowBtn.on('click', function() {
            addNewRow();
        })
    })

    // Add new row
    function addNewRow()
    {
        $.get("{{ route('customer.small-elements.product-row') }}", function(data) {
            addNewRowContainer.append(data)
        })
    }
</script>
@endsection