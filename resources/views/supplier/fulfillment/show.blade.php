@extends('supplier.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('supplier.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View Fulfillment Details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 pt-2 py-4">
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <!-- FULFILLMENT DETAILS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Fulfillment Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_search" class="mb-2 text-sm font-medium text-gray-900">Customer Owner</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full h-full py-2.5 px-2">{{ $customer['customer_id'] ?? '' }} - {{ $customer['full_name'] ?? 'Unknown' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['name'] ?? 'Not Provided'}}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['phone'] ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['address'] ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="address2" class="mb-2 text-sm font-medium text-gray-900">Address 2</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[41px]">{{ $fulfillment['address2'] ?? '' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="suburb" class="mb-2 text-sm font-medium text-gray-900">Suburb</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['suburb'] ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="state" class="mb-2 text-sm font-medium text-gray-900">State</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['state'] ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="postcode" class="mb-2 text-sm font-medium text-gray-900">Postcode</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['postcode'] ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="country" class="mb-2 text-sm font-medium text-gray-900">Country</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ CurrencyAndCountryEnum::MAP_COUNTRIES[$fulfillment['country']] ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="fulfillment_status" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ FulfillmentEnum::MAP_STATUS_COLORS[$fulfillment['fulfillment_status']] }}-600 text-sm w-full py-2.5 px-2">{{ FulfillmentEnum::MAP_FULFILLMENT_STATUSES[$fulfillment['fulfillment_status']] ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="product_payment_status" class="mb-2 text-sm font-medium text-gray-900">Product Payment Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ FulfillmentEnum::MAP_PAYMENT_COLORS[$fulfillment['product_payment_status']] }}-600 text-sm w-full py-2.5 px-2">{{ FulfillmentEnum::MAP_PAYMENT_STATUSES[$fulfillment['product_payment_status']] ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $fulfillment['note'] ?? '' }}</div>
                </div>
            </div>
            <!-- SHIPPING INFORMATION -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Shipping Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="labour_payment_status" class="mb-2 text-sm font-medium text-gray-900">Shipping Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ FulfillmentEnum::MAP_SHIPPING_COLORS[$fulfillment['shipping_status']] }}-600 text-sm w-full py-2.5 px-2">{{ Str::upper(FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']]) ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="shipping_type" class="mb-2 text-sm font-medium text-gray-900">Shipping Type</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ FulfillmentEnum::MAP_SHIPPING[$fulfillment['shipping_type']] ?? 'Not Provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="tracking_number" class="mb-2 text-sm font-medium text-gray-900">Tracking Number</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[41px]">{{ $fulfillment['tracking_number'] ?? '' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Amounts Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="postage" class="mb-2 text-sm font-medium text-gray-900">Total Product Amount</label>
                    <div class="w-full flex items-center text-sm bg-gray-50">
                        <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['total_product_amount'] ?? 0 }}</div>
                        <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-100 border-none pl-3">{{ $fulfillment['product_unit'] ?? '' }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="postage" class="mb-2 text-sm font-medium text-gray-900">Postage</label>
                    <div class="w-full flex items-center text-sm bg-gray-50">
                        <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $fulfillment['postage'] ?? 0 }}</div>
                        <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-100 border-none pl-3">{{ $fulfillment['postage_unit'] ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- PRODUCTS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Products Details
            </p>
            <div class="row flex flex-col gap-2 px-2.5 justify-center">
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
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('supplier.fulfillment.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('supplier.fulfillment.edit.form', ['fulfillment' => $fulfillment['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    @include('supplier.fulfillment.support-tickets')
    @include('supplier.fulfillment.add-payment')
    @include('supplier.fulfillment.payment-records')
</div>
@endsection