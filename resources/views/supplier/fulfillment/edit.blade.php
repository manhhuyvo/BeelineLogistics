@extends('supplier.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('supplier.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Edit Fulfillment Details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('supplier.fulfillment.update', ['fulfillment' => $fulfillment['id']]) }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <!-- FULFILLMENT DETAILS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Fulfillment Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900 opacity-50">Customer</label>
                    <input id="customer_id" type="text" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 opacity-50" value="{{ $fulfillment['customer']['customer_id'] ?? '' }} {{ $fulfillment['customer']['full_name'] }}" readonly>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="fulfillment_number" class="mb-2 text-sm font-medium text-gray-900">Full Number</label>
                    <input id="fulfillment_number" type="text" name="fulfillment_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ e($fulfillment['fulfillment_number'] ?? '') }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="name" type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $fulfillment['name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $fulfillment['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $fulfillment['address'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="address2" class="mb-2 text-sm font-medium text-gray-900">Address 2</label>
                    <input id="address2" type="text" name="address2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address 2" value="{{ $fulfillment['address2'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="suburb" class="mb-2 text-sm font-medium text-gray-900">Suburb</label>
                    <input id="suburb" type="text" name="suburb" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Suburb" value="{{ $fulfillment['suburb'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="state" class="mb-2 text-sm font-medium text-gray-900">State</label>
                    <input id="state" type="text" name="state" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="State" value="{{ $fulfillment['state'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="postcode" class="mb-2 text-sm font-medium text-gray-900">Postcode</label>
                    <input id="postcode" type="text" name="postcode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Postcode" value="{{ $fulfillment['postcode'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="country" class="mb-2 text-sm font-medium text-gray-900 opacity-50">Country</label>
                    <input id="country" type="text" name="country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 opacity-50" value="{{ $countries[$fulfillment['country']] ?? 'Unknown' }}" readonly>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="fulfillment_status" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Status</label>
                    <select id="fulfillment_status" name="fulfillment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($fulfillment['fulfillment_status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($fulfillmentStatuses as $key => $value)
                        @if (!empty($fulfillment['fulfillment_status']) && $fulfillment['fulfillment_status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="product_payment_status" class="mb-2 text-sm font-medium text-gray-900 opacity-50">Product Payment Status</label>
                    <input id="product_payment_status" type="text" name="product_payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 opacity-50" value="{{ $paymentStatuses[$fulfillment['product_payment_status']] ?? 'Unknown' }}" readonly>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Extra note" value="{{ $fulfillment['note'] ?? '' }}">{{ $fulfillment['note'] ?? '' }}</textarea>
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
                        @if (empty($fulfillment['shipping_status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach(FulfillmentEnum::MAP_SHIPPING_STATUSES as $key => $value)
                        @if (!empty($fulfillment['shipping_status']) && $fulfillment['shipping_status'] == $key)
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
                        @if (empty($fulfillment['shipping_type']))
                        <option selected disabled>Choose a shipping</option>
                        @endif
                    @foreach(FulfillmentEnum::MAP_SHIPPING as $key => $value)
                        @if (!empty($fulfillment['shipping_type']) && $fulfillment['shipping_type'] == $key)
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
                    <label for="tracking_number" class="mb-2 text-sm font-medium text-gray-900">Tracking Number</label>
                    <input id="tracking_number" type="text" name="tracking_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Tracking Number" value="{{ $fulfillment['tracking_number'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="postage" class="mb-2 text-sm font-medium text-gray-900">Postage</label>
                    <input id="postage" type="number" step="0.01" name="postage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Postage Fee" value="{{ $fulfillment['postage'] ?? 0 }}">
                </div>
            </div>
            <!-- PRODUCTS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Products Details
            </p>
            <div class="row flex flex-col gap-2 px-2.5 justify-center opacity-50">
                @foreach ($fulfillment['product_configs'] as $product)
                <div class="flex sm:flex-row sm:items-end flex-col gap-2 flex-1 bg-gray-100 border-solid border-[1px] border-gray-500 rounded-lg py-3 m-0 sm:gap-0 gap-3">
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Product Name</label>
                        <div class="bg-white border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $product['model']['name'] ?? '' }}</div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="mb-2 text-sm font-medium text-gray-900">Product Group</label>
                        <div class="bg-white border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $product['model']['product_group']['name'] ?? ''}}</div>
                    </div>
                    <div class="flex flex-col flex-2">
                        <label class="mb-2 text-sm font-medium text-gray-900">Quantity</label>
                        <div class="bg-white border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $product['quantity'] ?? 0 }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Update
                </button>
                <a href="{{ route('supplier.fulfillment.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection