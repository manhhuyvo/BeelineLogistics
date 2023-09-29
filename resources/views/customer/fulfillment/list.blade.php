@extends('customer.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('customer.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All fulfillments</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        <a href="{{ route('customer.fulfillment.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add fulfillment
        </a>
    </div>
    @include('customer.fulfillment.filter')    
    @if (empty($fulfillments['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('customer.layout.pagination')
    <div class="w-full overflow-x-auto mb-2" id="main-page-form">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
        <input type="hidden" id="bulk_action" name="bulk_action" value=""/>
        <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg min-width">
            <thead class="text-xs text-white font-semibold bg-indigo-950"  style="text-align: center !important;">
                <tr>
                    <th scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="select_all_rows" name="select_all_rows" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded">
                            <label for="select_all_rows" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="pl-4 sm:py-3 py-2">
                        Index
                    </th>
                    <th scope="col" class="px-4 sm:py-3 py-2">
                        <div class="flex items-center">
                            Current_Stage
                        </div>
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Fulfillment_ID
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Receiver_Name
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Receiver_Phone
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Receiver_Address
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Shipping_Type
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Tracking_Number
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Postage_Cost
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Fulfillment_Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Product_Amount
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Product_Payment_Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Labour_Cost
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Labour_Payment_Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Extra_Note
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Date_Created
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody style="text-align: center !important;">
                @foreach($fulfillments['data'] as $index => $fulfillment)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input name="selected_rows[]" type="checkbox" class="selected_rows w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded" value="{{ $fulfillment['id'] }}">
                            <label class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="col" class="pl-4 py-3">
                        {{ $index + 1 }}
                    </th>
                    <th scope="row" class=" py-4 font-medium whitespace-nowrap text-[12px]">
                        <span class="bg-{{ FulfillmentEnum::MAP_SHIPPING_COLORS[$fulfillment['shipping_status']] }}-500 py-2 px-3 rounded-lg text-white">{{ Str::upper(FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']]) }}</span>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $fulfillment['id'] }}
                    </th>
                    <td scope="row" class="px-6 py-4">
                        {{ $fulfillment['name'] ?? '' }}
                    </td>
                    <td scope="row" class="px-6 py-4">
                        {{ $fulfillment['phone'] ?? '' }}
                    </td>
                    <td scope="row" class="px-6 py-4">
                        {{ $fulfillment['address'] }},
                        @if (!empty($fulfillment['address2']))
                        {{ $fulfillment['address2'] }},
                        @endif
                        {{ $fulfillment['suburb'] }}
                        {{ $fulfillment['state'] }}
                        {{ $fulfillment['postcode'] }},
                        {{ $countries[$fulfillment['country']] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $shippingTypes[$fulfillment['shipping_type']] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $fulfillment['tracking_number'] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        @if (!empty($fulfillment['postage']))
                        {{ $fulfillment['postage'] ?? '' }} {{ $fulfillment['postage_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $fulfillmentStatusColors[$fulfillment['fulfillment_status']] }}-500 font-semibold">
                        {{ $fulfillmentStatuses[$fulfillment['fulfillment_status']] }}
                    </td>
                    <td class="px-6 py-4">
                        @if (!empty($fulfillment['total_product_amount']))
                        {{ $fulfillment['total_product_amount'] ?? '' }} {{ $fulfillment['product_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $paymentStatusColors[$fulfillment['product_payment_status']] }}-500 font-semibold">
                        {{ $paymentStatuses[$fulfillment['product_payment_status']] }}
                    </td>
                    <td class="px-6 py-4">
                        @if (!empty($fulfillment['total_labour_amount']))
                        {{ $fulfillment['total_labour_amount'] ?? '' }} {{ $fulfillment['labour_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $paymentStatusColors[$fulfillment['labour_payment_status']] }}-500 font-semibold">
                        {{ $paymentStatuses[$fulfillment['labour_payment_status']] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $fulfillment['note'] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $fulfillment['created_at'] }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-full flex gap-4">
                        <a href="{{ route('customer.fulfillment.show', ['fulfillment' => $fulfillment['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                        <a href="{{ route('customer.fulfillment.edit.form', ['fulfillment' => $fulfillment['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('customer.layout.main-page-action')
    @endif
</div>
@endsection